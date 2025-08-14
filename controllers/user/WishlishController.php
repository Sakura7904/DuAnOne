<?php
// controllers/user/WishlistController.php
include_once "models/user/WishlistModel.php";

class WishlistController
{
    private WishlistModel $wl;

    // Trang đăng nhập của CLIENT:
    private const LOGIN_URL = 'index.php?user=login';

    public function __construct()
    {
        $this->wl = new WishlistModel();
    }

    /* ---------------- Helpers ---------------- */

    private function requireLoginOrExit(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . self::LOGIN_URL);
            exit;
        }
    }

    private function backUrl(string $fallback = 'index.php?user=wishlist'): string
    {
        return $_SERVER['HTTP_REFERER'] ?? $fallback;
    }

    private function buildProductUrl(array $row): string
    {
        // Nếu SELECT có p.slug, có thể ưu tiên dùng slug ở đây.
        if (!empty($row['slug'])) {
            return '/' . rawurlencode($row['slug']) . '-p' . (int)$row['product_id'] . '.html';
        }
        return 'index.php?user=detailProduct&id=' . (int)$row['product_id'];
    }

    private function vnd($n): string
    {
        if ($n === null || $n === '') return 'Liên hệ';
        return number_format((float)$n, 0, ',', '.') . 'đ';
    }

    /* ---------------- Pages ---------------- */

    // GET: index.php?user=wishlist&pg=1&sort=newest
    public function index(): void
    {
        $this->requireLoginOrExit();

        $sort        = $_GET['sort'] ?? 'newest';              // newest | price_asc | price_desc
        $currentPage = isset($_GET['pg']) ? max(1, (int)$_GET['pg']) : 1;
        $perPage     = 12;
        $offset      = ($currentPage - 1) * $perPage;
        $uid         = (int)$_SESSION['user_id'];

        $total      = $this->wl->countByUser($uid);
        $rows       = $this->wl->getByUser($uid, $perPage, $offset, $sort);
        $totalPages = max(1, (int)ceil($total / $perPage));

        // Hydrate cho view
        foreach ($rows as &$r) {
            $r['product_url']       = $this->buildProductUrl($r);
            $r['display_price_str'] = $this->vnd($r['display_price'] ?? null);
            $r['price_str']         = $this->vnd($r['price'] ?? null);
            $r['sale_price_str']    = $this->vnd($r['sale_price'] ?? null);
            $r['in_stock']          = !empty($r['in_stock']);
            if (empty($r['image_url'])) $r['image_url'] = './assets/no-image.png';
        }
        unset($r);

        // Render qua layout client của bạn
        $content = getContentPathClient('', 'wishlist'); // views/user/pages/wishlist/index.php
        view('user/index', [
            'content'      => $content,
            'wishlist'     => $rows,
            'total'        => $total,
            'currentPage'  => $currentPage,
            'totalPages'   => $totalPages,
            'perPage'      => $perPage,
            'sort'         => $sort,
        ]);
    }

    /* ---------------- Actions (PHP thuần) ---------------- */

    /**
     * Toggle bằng PHP thuần.
     * Gọi qua:
     *  - POST/GET: index.php?user=toggleWishlist&product_id=...   (Home/Category)
     *  - POST/GET: index.php?user=toggleWishlist&variant_id=...   (Detail nếu đã có variant)
     */
    public function toggle(): void
    {
        $this->requireLoginOrExit();

        $uid = (int)$_SESSION['user_id'];
        // Nhận từ POST trước, nếu không có thì lấy GET (để link cũng chạy được)
        $variantId = (int)($_POST['variant_id'] ?? $_GET['variant_id'] ?? 0);
        $productId = (int)($_POST['product_id'] ?? $_GET['product_id'] ?? 0);

        if ($variantId > 0) {
            $this->wl->toggle($uid, $variantId);
        } elseif ($productId > 0) {
            // Toggle theo product thông minh: nếu sp đã có biến thể trong wishlist -> xoá; chưa có -> thêm biến thể mặc định
            $this->wl->toggleByProduct($uid, $productId);
        }
        // Thiếu param -> cứ quay về trang trước
        header('Location: ' . $this->backUrl());
        exit;
    }

    // GET: index.php?user=addToWishlist&variant_id=123  (hoặc &product_id=45)
    public function add(): void
    {
        $this->requireLoginOrExit();

        $uid = (int)$_SESSION['user_id'];
        $variantId = (int)($_GET['variant_id'] ?? 0);
        $productId = (int)($_GET['product_id'] ?? 0);

        if ($variantId > 0) {
            $this->wl->add($uid, $variantId);
        } elseif ($productId > 0) {
            // thêm theo variant mặc định
            $vid = $this->wl->getDefaultVariantIdForProduct($productId);
            if ($vid) $this->wl->add($uid, $vid);
        }

        header('Location: ' . $this->backUrl());
        exit;
    }

    // GET: index.php?user=removeFromWishlist&variant_id=123  (hoặc &product_id=45)
    public function remove(): void
    {
        $this->requireLoginOrExit();

        $uid = (int)$_SESSION['user_id'];
        $variantId = (int)($_GET['variant_id'] ?? 0);
        $productId = (int)($_GET['product_id'] ?? 0);

        if ($variantId > 0) {
            $this->wl->remove($uid, $variantId);
        } elseif ($productId > 0) {
            // Xoá theo biến thể mặc định của sản phẩm (đơn giản). Nếu muốn xoá tất cả biến thể của sp:
            // $this->wl->removeAllVariantsOfProduct($uid, $productId);
            $vid = $this->wl->getDefaultVariantIdForProduct($productId);
            if ($vid) $this->wl->remove($uid, $vid);
        }

        header('Location: ' . $this->backUrl());
        exit;
    }

    // (Tuỳ chọn) GET: index.php?user=countWishlist  → trả plain text số lượng
    public function count(): void
    {
        $this->requireLoginOrExit();
        header('Content-Type: text/plain; charset=utf-8');
        $uid = (int)$_SESSION['user_id'];
        echo (string)$this->wl->countByUser($uid);
        exit;
    }

    // (Tuỳ chọn) POST/GET: index.php?user=clearWishlist  → xoá hết wishlist
    public function clear(): void
    {
        $this->requireLoginOrExit();
        $uid = (int)$_SESSION['user_id'];
        $this->wl->clear($uid);
        header('Location: ' . $this->backUrl());
        exit;
    }
}
