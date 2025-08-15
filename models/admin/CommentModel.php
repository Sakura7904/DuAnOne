<?php

class CommentModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Paginate comments for Admin list
     * - Bảo vệ khi page vượt quá tổng trang (auto kẹp lại)
     * - JOIN products & users để hiện tên
     */
    public function paginate(int $page = 1, int $perPage = 20): array {
        // Đếm tổng
        $countSql = "SELECT COUNT(*) FROM comments";
        $total = (int)$this->db->pdo->query($countSql)->fetchColumn();

        // Tính tổng trang (>=1)
        $totalPages = max(1, (int)ceil($total / max(1, $perPage)));

        // Kẹp page về khoảng hợp lệ [1 .. totalPages]
        $page = max(1, min($page, $totalPages));

        // Tính offset
        $offset = ($page - 1) * $perPage;

        // Lấy data (JOIN để hiện tên SP & User)
        $sql = "
            SELECT
                c.id, c.product_id, c.user_id, c.content, c.created_at, c.updated_at,
                p.name       AS product_name,
                u.full_name  AS user_name,
                u.email      AS email
            FROM comments c
            JOIN products p ON p.id = c.product_id
            JOIN users    u ON u.id = c.user_id
            ORDER BY c.created_at DESC, c.id DESC
            LIMIT :limit OFFSET :offset
        ";

        $st = $this->db->pdo->prepare($sql);
        $st->bindValue(':limit',  $perPage, PDO::PARAM_INT);
        $st->bindValue(':offset', $offset,  PDO::PARAM_INT);
        $st->execute();
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data'       => $rows,
            'total'      => $total,
            'page'       => $page,
            'perPage'    => $perPage,
            'totalPages' => $totalPages,
        ];
    }

    public function delete(int $id): bool {
        $st = $this->db->pdo->prepare("DELETE FROM comments WHERE id = :id");
        return $st->execute([':id' => $id]);
    }


    // Dùng ở frontend để thêm bình luận
    public function create(int $userId, int $productId, string $content): bool {
        $sql = "INSERT INTO comments (product_id, user_id, content)
                VALUES (:pid, :uid, :content)";
        $st = $this->db->pdo->prepare($sql);
        return $st->execute([
            ':pid'     => $productId,
            ':uid'     => $userId,
            ':content' => trim($content),
        ]);
    }
}
