<?php
class UserProductModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllCategories()
    {
        $sql = "SELECT * FROM categories ORDER BY id ASC";
        $stmt = $this->db->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryById($id)
    {
        $sql = "SELECT * FROM categories WHERE id = :id LIMIT 1";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getLatest($limit = 8)
    {
        $sql = "
            SELECT 
                p.id, 
                p.name,
                p.description,
                COALESCE(
                    p.image_thumbnail,
                    (
                        SELECT v.image_url 
                        FROM productvariants v 
                        WHERE v.product_id = p.id 
                          AND v.image_url IS NOT NULL 
                          AND v.image_url <> '' 
                        ORDER BY v.id ASC
                        LIMIT 1
                    )
                ) AS image_url,
                (
                    SELECT MIN(v.price)
                    FROM productvariants v
                    WHERE v.product_id = p.id
                ) AS price,
                (
                    SELECT MIN(v.sale_price)
                    FROM productvariants v
                    WHERE v.product_id = p.id AND v.sale_price IS NOT NULL
                ) AS sale_price,
                p.created_at
            FROM products p
            ORDER BY p.created_at DESC
            LIMIT :limit
        ";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "
            SELECT 
                p.*, 
                COALESCE(
                    p.image_thumbnail,
                    (
                        SELECT v.image_url 
                        FROM productvariants v 
                        WHERE v.product_id = p.id AND v.image_url IS NOT NULL AND v.image_url <> '' 
                        LIMIT 1
                    )
                ) AS image_url,
                (
                    SELECT MIN(v.price) 
                    FROM productvariants v WHERE v.product_id = p.id
                ) AS price,
                (
                    SELECT MIN(v.sale_price)
                    FROM productvariants v WHERE v.product_id = p.id AND v.sale_price IS NOT NULL
                ) AS sale_price
            FROM products p
            WHERE p.id = :id
            LIMIT 1
        ";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProductColors($productId)
    {
        $sql = "
            SELECT 
                v.id as variant_id,
                av.value AS color_name,
                av.color_code,
                v.image_url
            FROM productvariants v
            INNER JOIN productvariantvalues vv ON v.id = vv.variant_id
            INNER JOIN attributevalues av ON vv.value_id = av.id
            WHERE v.product_id = :product_id
              AND av.attribute_id = 1
            GROUP BY v.id, av.value, av.color_code, v.image_url
        ";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** ========================== PHẦN MỚI ========================== **/

    private function getAllChildCategoryIdsInternal(int $parentId): array
    {
        $sql = "SELECT id FROM categories WHERE parent_id = :pid";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindValue(':pid', $parentId, PDO::PARAM_INT);
        $stmt->execute();
        $childIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $all = [];
        foreach ($childIds as $cid) {
            $cid = (int)$cid;
            $all[] = $cid;
            $all = array_merge($all, $this->getAllChildCategoryIdsInternal($cid));
        }
        return $all;
    }

    public function countProductsByCategory($categoryId)
    {
        $ids = array_merge([(int)$categoryId], $this->getAllChildCategoryIdsInternal((int)$categoryId));
        $ids = array_values(array_unique($ids));
        if (empty($ids)) return 0;

        $ph = [];
        foreach ($ids as $i => $val) $ph[] = ':id'.$i;
        $in = implode(',', $ph);

        $sql = "SELECT COUNT(*) FROM products WHERE category_id IN ($in)";
        $stmt = $this->db->pdo->prepare($sql);
        foreach ($ids as $i => $val) {
            $stmt->bindValue(':id'.$i, (int)$val, PDO::PARAM_INT);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function getProductsByCategory($categoryId, $sort = 'newest', $limit = 12, $offset = 0)
    {
        $orderBy = "p.created_at DESC";
        if ($sort === 'low_to_high') {
            $orderBy = "
                COALESCE(
                    (SELECT MIN(v.sale_price) FROM productvariants v WHERE v.product_id = p.id AND v.sale_price IS NOT NULL),
                    (SELECT MIN(v.price)      FROM productvariants v WHERE v.product_id = p.id)
                ) ASC
            ";
        } elseif ($sort === 'high_to_low') {
            $orderBy = "
                COALESCE(
                    (SELECT MIN(v.sale_price) FROM productvariants v WHERE v.product_id = p.id AND v.sale_price IS NOT NULL),
                    (SELECT MIN(v.price)      FROM productvariants v WHERE v.product_id = p.id)
                ) DESC
            ";
        }

        $ids = array_merge([(int)$categoryId], $this->getAllChildCategoryIdsInternal((int)$categoryId));
        $ids = array_values(array_unique($ids));
        if (empty($ids)) return [];

        $ph = [];
        foreach ($ids as $i => $val) $ph[] = ':id'.$i;
        $in = implode(',', $ph);

        $limit  = max(1, (int)$limit);
        $offset = max(0, (int)$offset);

        $sql = "
            SELECT 
                p.id, 
                p.name,
                p.description,
                p.category_id,
                COALESCE(
                    p.image_thumbnail,
                    (
                        SELECT v.image_url 
                        FROM productvariants v 
                        WHERE v.product_id = p.id 
                          AND v.image_url IS NOT NULL 
                          AND v.image_url <> '' 
                        ORDER BY v.id ASC
                        LIMIT 1
                    )
                ) AS image_url,
                (
                    SELECT MIN(v.price)
                    FROM productvariants v
                    WHERE v.product_id = p.id
                ) AS price,
                (
                    SELECT MIN(v.sale_price)
                    FROM productvariants v
                    WHERE v.product_id = p.id AND v.sale_price IS NOT NULL
                ) AS sale_price,
                p.created_at
            FROM products p
            WHERE p.category_id IN ($in)
            ORDER BY $orderBy
            LIMIT $limit OFFSET $offset
        ";

        $stmt = $this->db->pdo->prepare($sql);
        foreach ($ids as $i => $val) {
            $stmt->bindValue(':id'.$i, (int)$val, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** ========================== PHẦN CŨ GIỮ NGUYÊN ========================== **/

    public function getCategoriesWithChildren()
    {
        $sql = "SELECT * FROM categories ORDER BY parent_id, id";
        $stmt = $this->db->pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tree = [];
        foreach ($rows as $row) {
            if ($row['parent_id'] === null) {
                $tree[$row['id']] = $row;
                $tree[$row['id']]['children'] = [];
            } else {
                $tree[$row['parent_id']]['children'][] = $row;
            }
        }
        return $tree;
    }

    public function searchProduct(string $keyword, int $limit = 100, int $offset = 0): array
    {
        $sql = "
            SELECT 
                p.id, 
                p.name,
                p.description,
                p.category_id,
                COALESCE(
                    p.image_thumbnail,
                    (
                        SELECT v.image_url 
                        FROM productvariants v 
                        WHERE v.product_id = p.id 
                          AND v.image_url IS NOT NULL 
                          AND v.image_url <> '' 
                        ORDER BY v.id ASC
                        LIMIT 1
                    )
                ) AS image_url,
                (
                    SELECT MIN(v.price)
                    FROM productvariants v
                    WHERE v.product_id = p.id
                ) AS price,
                (
                    SELECT MIN(v.sale_price)
                    FROM productvariants v
                    WHERE v.product_id = p.id AND v.sale_price IS NOT NULL
                ) AS sale_price,
                p.created_at
            FROM products p
            WHERE p.name LIKE :kw
            ORDER BY p.created_at DESC
            LIMIT :limit OFFSET :offset
        ";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindValue(':kw', '%' . trim($keyword) . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
