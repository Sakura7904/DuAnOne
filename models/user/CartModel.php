<?php
class CartModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Lấy giỏ hàng của user theo user_id
     */
    public function getCartByUserId($userId)
    {
        $sql = "SELECT * FROM carts WHERE user_id = :user_id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Tạo giỏ hàng mới cho user
     */
    public function createCart($userId)
    {
        $sql = "INSERT INTO carts (user_id) VALUES (:user_id)";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $this->db->pdo->lastInsertId();
    }

    /**
     * Lấy hoặc tạo giỏ hàng cho user
     */
    public function getOrCreateCart($userId)
    {
        $cart = $this->getCartByUserId($userId);
        if (!$cart) {
            $cartId = $this->createCart($userId);
            return ['id' => $cartId, 'user_id' => $userId];
        }
        return $cart;
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function addToCart($cartId, $variantId, $quantity = 1)
    {
        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $existingItem = $this->getCartItem($cartId, $variantId);

        if ($existingItem) {
            // Nếu đã có thì cập nhật quantity
            $newQuantity = $existingItem['quantity'] + $quantity;
            return $this->updateCartItemQuantity($existingItem['id'], $newQuantity);
        } else {
            // Nếu chưa có thì thêm mới
            $sql = "INSERT INTO cartitems (cart_id, variant_id, quantity) VALUES (:cart_id, :variant_id, :quantity)";
            $stmt = $this->db->pdo->prepare($sql);
            $stmt->bindParam(':cart_id', $cartId, PDO::PARAM_INT);
            $stmt->bindParam(':variant_id', $variantId, PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            return $stmt->execute();
        }
    }

    /**
     * Lấy item trong giỏ hàng theo cart_id và variant_id
     */
    public function getCartItem($cartId, $variantId)
    {
        $sql = "SELECT * FROM cartitems WHERE cart_id = :cart_id AND variant_id = :variant_id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':cart_id', $cartId, PDO::PARAM_INT);
        $stmt->bindParam(':variant_id', $variantId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cập nhật số lượng của item trong giỏ hàng
     */
    public function updateCartItemQuantity($cartItemId, $quantity)
    {
        if ($quantity <= 0) {
            return $this->removeCartItem($cartItemId);
        }

        $sql = "UPDATE cartitems SET quantity = :quantity WHERE id = :cart_item_id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':cart_item_id', $cartItemId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Xóa item khỏi giỏ hàng
     */
    public function removeCartItem($cartItemId)
    {
        $sql = "DELETE FROM cartitems WHERE id = :cart_item_id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':cart_item_id', $cartItemId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Lấy tất cả items trong giỏ hàng với thông tin chi tiết
     */
    public function getCartItems($cartId)
    {
        $sql = "
            SELECT 
                ci.id as cart_item_id,
                ci.quantity,
                pv.id as variant_id,
                pv.price,
                pv.quantity AS stock,  
                pv.sale_price,
                pv.image_url as variant_image,
                p.id as product_id,
                p.name as product_name,
                p.image_thumbnail,
                GROUP_CONCAT(CONCAT(a.name, ': ', av.value) SEPARATOR ', ') as variant_attributes
            FROM cartitems ci
            INNER JOIN productvariants pv ON ci.variant_id = pv.id
            INNER JOIN products p ON pv.product_id = p.id
            LEFT JOIN productvariantvalues pvv ON pv.id = pvv.variant_id
            LEFT JOIN attributevalues av ON pvv.value_id = av.id
            LEFT JOIN attributes a ON av.attribute_id = a.id
            WHERE ci.cart_id = :cart_id
            GROUP BY ci.id, pv.id, p.id
            ORDER BY ci.id DESC
        ";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':cart_id', $cartId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Đếm số lượng items trong giỏ hàng
     */
    public function getCartItemCount($cartId)
    {
        $sql = "SELECT SUM(quantity) as total_items FROM cartitems WHERE cart_id = :cart_id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':cart_id', $cartId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_items'] ?? 0;
    }

    /**
     * Tính tổng giá trị giỏ hàng
     */
    public function getCartTotal($cartId)
    {
        $sql = "
            SELECT SUM(
                ci.quantity * COALESCE(pv.sale_price, pv.price)
            ) as total_amount
            FROM cartitems ci
            INNER JOIN productvariants pv ON ci.variant_id = pv.id
            WHERE ci.cart_id = :cart_id
        ";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':cart_id', $cartId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_amount'] ?? 0;
    }

    /**
     * Xóa tất cả items trong giỏ hàng
     */
    public function clearCart($cartId)
    {
        $sql = "DELETE FROM cartitems WHERE cart_id = :cart_id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':cart_id', $cartId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Kiểm tra tính khả dụng của sản phẩm trong kho
     */
    public function checkProductAvailability($variantId, $requestedQuantity)
    {
        $sql = "SELECT quantity FROM productvariants WHERE id = :variant_id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':variant_id', $variantId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return false;
        }

        return $result['quantity'] >= $requestedQuantity;
    }

    /**
     * Cập nhật thời gian modified của cart
     */
    public function updateCartTimestamp($cartId)
    {
        $sql = "UPDATE carts SET updated_at = CURRENT_TIMESTAMP WHERE id = :cart_id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':cart_id', $cartId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Lấy thông tin chi tiết của một cart item
     */
    public function getCartItemDetail(int $cartItemId): ?array
    {
        $sql = "
        SELECT 
            ci.id          AS cart_item_id,
            ci.cart_id,
            ci.variant_id,
            ci.quantity    AS cart_quantity,
            v.quantity     AS stock
        FROM cartitems ci
        INNER JOIN productvariants v ON v.id = ci.variant_id
        WHERE ci.id = :cid
        LIMIT 1
    ";
        $st = $this->db->pdo->prepare($sql);
        $st->bindValue(':cid', $cartItemId, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }


    /**
     * Lấy màu sắc của sản phẩm (như ví dụ bạn đưa)
     */
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
              AND av.attribute_id = 1 -- 1 = màu sắc
            GROUP BY v.id, av.value, av.color_code, v.image_url
        ";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCartItemWithStock(int $userId, int $cartItemId): ?array
    {
        $sql = "
            SELECT 
                ci.id        AS cart_item_id,
                ci.variant_id,
                ci.quantity  AS cart_qty,
                v.quantity   AS stock,
                v.price,
                v.sale_price,
                v.image_url
            FROM cart_items ci
            INNER JOIN productvariants v ON v.id = ci.variant_id
            WHERE ci.id = :cid AND ci.user_id = :uid
            LIMIT 1
        ";
        $st = $this->db->pdo->prepare($sql);
        $st->bindValue(':cid', $cartItemId, PDO::PARAM_INT);
        $st->bindValue(':uid', $userId, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /** Lấy tồn kho theo variant_id (khi cần) */
    public function getStockByVariant(int $variantId): int
    {
        $st = $this->db->pdo->prepare("SELECT quantity FROM productvariants WHERE id = :vid LIMIT 1");
        $st->bindValue(':vid', $variantId, PDO::PARAM_INT);
        $st->execute();
        $stock = $st->fetchColumn();
        return (int)($stock ?: 0);
    }

    /** Dùng khi render giỏ: lấy list items kèm stock */
    public function getCartItemsWithStock(int $userId): array
    {
        $sql = "
            SELECT 
                ci.id AS cart_item_id,
                ci.variant_id,
                ci.quantity,
                v.quantity AS stock,
                v.price, v.sale_price, v.image_url
            FROM cart_items ci
            INNER JOIN productvariants v ON v.id = ci.variant_id
            WHERE ci.user_id = :uid
            ORDER BY ci.id DESC
        ";
        $st = $this->db->pdo->prepare($sql);
        $st->bindValue(':uid', $userId, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }
}
