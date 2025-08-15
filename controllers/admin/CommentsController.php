<?php
include_once "models/admin/CommentModel.php";
class CommentsController {
    private $model;

    public function __construct() {
        $this->model = new CommentModel();
    }

    public function index() {
        // Dùng ?p= (như bạn đã đổi)
        $page    = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
        $perPage = 2;

        $result   = $this->model->paginate($page, $perPage);
        $comments = $result['data'] ?? [];

        // Tạo cấu trúc phân trang giống Product list
        $totalPages = (int)($result['totalPages'] ?? 1);
        $pg = [
            'total'   => $totalPages,
            'current' => (int)($result['page'] ?? 1),
            'hasPrev' => $page > 1,
            'prev'    => max(1, $page - 1),
            'hasNext' => $page < $totalPages,
            'next'    => min($totalPages, $page + 1),
        ];
        $content = "views/admin/pages/comments/list.php";
        include "views/admin/master.php";
    }

    public function delete() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->model->delete($id);
            $_SESSION['msg'] = 'Đã xóa bình luận thành công.'; $_SESSION['msg_type'] = 'success';
        }
        header("Location: index.php?admin=list_comments&p=" . ((int)($_GET['p'] ?? 1)));
        exit;
    }
}
