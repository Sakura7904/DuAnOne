<?php
class CommentClientModel
{
    private $db;

    public function __construct()
    {
        // Database của bạn đã có sẵn (PDO)
        $this->db = new Database();
    }

    // Đếm tổng bình luận theo sản phẩm (để phân trang)
    public function countByProduct(int $productId): int
    {
        $sql = "SELECT COUNT(*) FROM comments WHERE product_id = :pid";
        $st  = $this->db->pdo->prepare($sql);
        $st->bindValue(':pid', $productId, PDO::PARAM_INT);
        $st->execute();
        return (int)$st->fetchColumn();
    }

    // Lấy danh sách bình luận theo sản phẩm + tên user (phân trang)
    public function getByProduct(int $productId, int $limit = 8, int $offset = 0): array
    {
        $sql = "
            SELECT
                c.id, c.product_id, c.user_id, c.content, c.created_at,
                u.full_name
            FROM comments c
            JOIN users u ON u.id = c.user_id
            WHERE c.product_id = :pid
            ORDER BY c.created_at DESC, c.id DESC
            LIMIT :limit OFFSET :offset
        ";
        $st = $this->db->pdo->prepare($sql);
        $st->bindValue(':pid',   $productId, PDO::PARAM_INT);
        $st->bindValue(':limit', $limit,     PDO::PARAM_INT);
        $st->bindValue(':offset',$offset,    PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy 1 bình luận theo id (để check quyền khi sửa/xóa)
    public function getById(int $id): ?array
    {
        $sql = "SELECT * FROM comments WHERE id = :id";
        $st  = $this->db->pdo->prepare($sql);
        $st->bindValue(':id', $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    // Thêm bình luận mới
    public function create(int $productId, int $userId, string $content): bool
    {
        $sql = "INSERT INTO comments (product_id, user_id, content) VALUES (:pid, :uid, :content)";
        $st  = $this->db->pdo->prepare($sql);
        $st->bindValue(':pid',     $productId, PDO::PARAM_INT);
        $st->bindValue(':uid',     $userId,    PDO::PARAM_INT);
        $st->bindValue(':content', $content,   PDO::PARAM_STR);
        return $st->execute();
    }

    // Sửa nội dung bình luận
    public function update(int $id, string $content): bool
    {
        $sql = "UPDATE comments SET content = :content WHERE id = :id";
        $st  = $this->db->pdo->prepare($sql);
        $st->bindValue(':id',      $id,      PDO::PARAM_INT);
        $st->bindValue(':content', $content, PDO::PARAM_STR);
        return $st->execute();
    }

    // Xóa bình luận
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM comments WHERE id = :id";
        $st  = $this->db->pdo->prepare($sql);
        $st->bindValue(':id', $id, PDO::PARAM_INT);
        return $st->execute();
    }
}
