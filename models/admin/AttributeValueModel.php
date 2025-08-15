<?php

class AttributeValueModel
{
    /** @var Database */
    private $db;

    public function __construct()
    {
        $this->db = new Database(); // $this->db->pdo là PDO
    }

    /* ===================== QUERY HELPERS ===================== */

    /** Lấy 1 bản ghi theo id */
    public function find(int $id): ?array
    {
        $sql = "SELECT av.*, a.name AS attribute_name
                FROM attributevalues av
                JOIN attributes a ON a.id = av.attribute_id
                WHERE av.id = :id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Liệt kê + filter + phân trang + sắp xếp
     * $filters = ['attribute_id' => 1, 'q' => 'đen']
     */
    public function list(array $filters = [], int $page = 1, int $perPage = 20, string $sort = 'av.id DESC'): array
    {
        $where = [];
        $params = [];

        if (!empty($filters['attribute_id'])) {
            $where[] = 'av.attribute_id = :attribute_id';
            $params[':attribute_id'] = (int)$filters['attribute_id'];
        }
        if (!empty($filters['q'])) {
            $where[] = '(av.value LIKE :q)';
            $params[':q'] = '%' . $filters['q'] . '%';
        }

        $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';
        $sortSafe = preg_match('/^[a-zA-Z0-9_\\.\\s,]+(ASC|DESC)?$/i', $sort) ? $sort : 'av.id DESC';

        $offset = max(0, ($page - 1) * $perPage);
        $perPage = max(1, min(200, $perPage));

        $sql = "SELECT SQL_CALC_FOUND_ROWS av.*, a.name AS attribute_name
                FROM attributevalues av
                JOIN attributes a ON a.id = av.attribute_id
                $whereSql
                ORDER BY $sortSafe
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->pdo->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $total = (int)$this->db->pdo->query("SELECT FOUND_ROWS()")->fetchColumn();

        return [
            'data' => $items,
            'pagination' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => (int)ceil($total / $perPage),
            ],
        ];
    }

    /** Lấy tất cả value theo attribute_id (phục vụ dropdown) */
    public function getByAttribute(int $attributeId): array
    {
        $sql = "SELECT id, value, color_code FROM attributevalues
                WHERE attribute_id = :aid
                ORDER BY value ASC";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindValue(':aid', $attributeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===================== CREATE / UPDATE ===================== */

    /**
     * Tạo mới
     * $data = ['attribute_id'=>1, 'value'=>'Đen', 'color_code'=>'#000000']
     */
    public function create(array $data): int
    {
        $clean = $this->validateData($data);

        // Chống trùng value trong cùng attribute
        if ($this->existsDuplicate($clean['attribute_id'], $clean['value'])) {
            throw new InvalidArgumentException('Giá trị đã tồn tại trong thuộc tính này.');
        }

        $sql = "INSERT INTO attributevalues (attribute_id, value, color_code)
                VALUES (:attribute_id, :value, :color_code)";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindValue(':attribute_id', $clean['attribute_id'], PDO::PARAM_INT);
        $stmt->bindValue(':value', $clean['value'], PDO::PARAM_STR);
        $stmt->bindValue(':color_code', $clean['color_code'], $clean['color_code'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->execute();

        return (int)$this->db->pdo->lastInsertId();
    }

    /**
     * Cập nhật
     * $data = ['attribute_id'=>1, 'value'=>'Trắng', 'color_code'=>null]
     */
    public function update(int $id, array $data): bool
    {
        $clean = $this->validateData($data);

        if ($this->existsDuplicate($clean['attribute_id'], $clean['value'], $id)) {
            throw new InvalidArgumentException('Giá trị đã tồn tại trong thuộc tính này.');
        }

        $sql = "UPDATE attributevalues
                SET attribute_id = :attribute_id,
                    value = :value,
                    color_code = :color_code
                WHERE id = :id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindValue(':attribute_id', $clean['attribute_id'], PDO::PARAM_INT);
        $stmt->bindValue(':value', $clean['value'], PDO::PARAM_STR);
        $stmt->bindValue(':color_code', $clean['color_code'], $clean['color_code'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /* ===================== DELETE ===================== */

    /**
     * Xoá: do có FK ON DELETE CASCADE từ productvariantvalues → attributevalues,
     * xoá value sẽ tự xoá các mapping liên quan.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->pdo->prepare("DELETE FROM attributevalues WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /* ===================== VALIDATION & UTIL ===================== */

    private function validateData(array $data): array
    {
        $attribute_id = isset($data['attribute_id']) ? (int)$data['attribute_id'] : 0;
        $value = isset($data['value']) ? trim((string)$data['value']) : '';
        $color_code = $data['color_code'] ?? null;
        $color_code = ($color_code === '' || $color_code === null) ? null : strtoupper(trim((string)$color_code));

        if ($attribute_id <= 0) {
            throw new InvalidArgumentException('attribute_id không hợp lệ.');
        }
        // Kiểm tra attribute tồn tại
        if (!$this->attributeExists($attribute_id)) {
            throw new InvalidArgumentException('Thuộc tính không tồn tại.');
        }
        if ($value === '' || mb_strlen($value) > 100) {
            throw new InvalidArgumentException('value bắt buộc (<= 100 ký tự).');
        }
        // color_code nếu có phải là #RRGGBB (7 ký tự)
        if ($color_code !== null && !preg_match('/^#[0-9A-F]{6}$/i', $color_code)) {
            throw new InvalidArgumentException('color_code phải dạng #RRGGBB (VD: #FFFFFF).');
        }

        return [
            'attribute_id' => $attribute_id,
            'value' => $value,
            'color_code' => $color_code,
        ];
    }

    private function attributeExists(int $attributeId): bool
    {
        $stmt = $this->db->pdo->prepare("SELECT 1 FROM attributes WHERE id = :id");
        $stmt->bindValue(':id', $attributeId, PDO::PARAM_INT);
        $stmt->execute();
        return (bool)$stmt->fetchColumn();
    }

    /** Kiểm tra trùng value trong cùng attribute_id (bỏ qua $excludeId khi update) */
    private function existsDuplicate(int $attributeId, string $value, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM attributevalues
                WHERE attribute_id = :aid AND value = :val";
        if ($excludeId) {
            $sql .= " AND id <> :id";
        }
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindValue(':aid', $attributeId, PDO::PARAM_INT);
        $stmt->bindValue(':val', $value, PDO::PARAM_STR);
        if ($excludeId) {
            $stmt->bindValue(':id', $excludeId, PDO::PARAM_INT);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn() > 0;
    }
}
