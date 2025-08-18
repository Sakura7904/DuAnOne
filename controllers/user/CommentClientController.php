<?php
include_once "models/user/CommentClientModel.php";

class CommentClientController
{
    private function mustLogin(): void {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['msg'] = 'Vui lòng đăng nhập để sử dụng bình luận.';
            $_SESSION['msg_type'] = 'error';
            header("Location: index.php?user=loginForm");
            exit;
        }
    }

    private function backToProduct(int $productId, int $cmtPage = 1): void {
        // Sửa: đúng route của bạn là detailProduct
        header("Location: index.php?user=detailProduct&id={$productId}&cmt_page={$cmtPage}");
        exit;
    }

    // POST /index.php?user=addComment
    public function add(): void {
        $this->mustLogin();
        $pid     = (int)($_POST['product_id'] ?? 0);
        $content = trim($_POST['content'] ?? '');
        $cmtPage = max(1, (int)($_POST['cmt_page'] ?? 1));

        if ($pid <= 0) { http_response_code(400); die('product_id không hợp lệ'); }
        if ($content === '') {
            $_SESSION['msg'] = 'Nội dung không được để trống.';
            $_SESSION['msg_type'] = 'error';
            $this->backToProduct($pid, $cmtPage);
        }

        $m  = new CommentClientModel();
        $ok = $m->create($pid, (int)$_SESSION['user_id'], $content);
        $_SESSION['msg'] = $ok ? 'Đã đăng bình luận.' : 'Không thể lưu bình luận.';
        $_SESSION['msg_type'] = $ok ? 'success' : 'error';
        $this->backToProduct($pid, $cmtPage);
    }

    // POST /index.php?user=updateComment
    public function update(): void {
        $this->mustLogin();
        $pid      = (int)($_POST['product_id'] ?? 0);
        $cid      = (int)($_POST['comment_id'] ?? 0);
        $content  = trim($_POST['content'] ?? '');
        $cmtPage  = max(1, (int)($_POST['cmt_page'] ?? 1));
        if ($pid <= 0 || $cid <= 0) { http_response_code(400); die('Thiếu tham số'); }

        $m   = new CommentClientModel();
        $row = $m->getById($cid);
        if (!$row || (int)$row['product_id'] !== $pid) {
            $_SESSION['msg'] = 'Bình luận không tồn tại.';
            $_SESSION['msg_type'] = 'error';
            $this->backToProduct($pid, $cmtPage);
        }

        // Chỉ CHỦ bình luận được sửa (KHÔNG dính admin)
        if ((int)$row['user_id'] !== (int)$_SESSION['user_id']) {
            $_SESSION['msg'] = 'Bạn không có quyền sửa bình luận này.';
            $_SESSION['msg_type'] = 'error';
            $this->backToProduct($pid, $cmtPage);
        }

        if ($content === '') {
            $_SESSION['msg'] = 'Nội dung không được để trống.';
            $_SESSION['msg_type'] = 'error';
            $this->backToProduct($pid, $cmtPage);
        }

        $ok = $m->update($cid, $content);
        $_SESSION['msg'] = $ok ? 'Đã cập nhật bình luận.' : 'Cập nhật thất bại.';
        $_SESSION['msg_type'] = $ok ? 'success' : 'error';
        $this->backToProduct($pid, $cmtPage);
    }

    // POST /index.php?user=deleteComment
    public function delete(): void {
        $this->mustLogin();
        $pid     = (int)($_POST['product_id'] ?? 0);
        $cid     = (int)($_POST['comment_id'] ?? 0);
        $cmtPage = max(1, (int)($_POST['cmt_page'] ?? 1));
        if ($pid <= 0 || $cid <= 0) { http_response_code(400); die('Thiếu tham số'); }

        $m   = new CommentClientModel();
        $row = $m->getById($cid);
        if (!$row || (int)$row['product_id'] !== $pid) {
            $_SESSION['msg'] = 'Bình luận không tồn tại.';
            $_SESSION['msg_type'] = 'error';
            $this->backToProduct($pid, $cmtPage);
        }

        // Chỉ CHỦ bình luận được xoá
        if ((int)$row['user_id'] !== (int)$_SESSION['user_id']) {
            $_SESSION['msg'] = 'Bạn không có quyền xóa bình luận này.';
            $_SESSION['msg_type'] = 'error';
            $this->backToProduct($pid, $cmtPage);
        }

        $ok = $m->delete($cid);
        $_SESSION['msg'] = $ok ? 'Đã xóa bình luận.' : 'Xóa thất bại.';
        $_SESSION['msg_type'] = $ok ? 'success' : 'error';
        $this->backToProduct($pid, $cmtPage);
    }
}
