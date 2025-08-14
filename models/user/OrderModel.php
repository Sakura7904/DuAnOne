<?php

class OrderModel
{
    /** @var Database */
    public $db;

    public function __construct()
    {
        $this->db = new Database(); // $this->db->pdo là PDO
    }

    /* =======================
     * Helpers (private)
     * ======================= */
    // ======================
    // LẤY GIỎ HÀNG CHI TIẾT
    // ======================
    public function getCartItemsDetailed($userId)
    {
        // lấy cart_id
        $sql = "SELECT id FROM carts WHERE user_id = :user_id LIMIT 1";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $cartId = (int)$stmt->fetchColumn();
        if (!$cartId) return [];

        $sql = "
        SELECT 
            ci.variant_id,
            ci.quantity,
            p.name AS product_name,
            p.image_thumbnail AS image_url,           -- ảnh thumbnail từ products
            pv.price       AS original_price,
            pv.sale_price  AS sale_price,
            COALESCE(pv.sale_price, pv.price) AS effective_price,
            MAX(CASE WHEN av.attribute_id = 1 THEN av.value END) AS color_name,
            MAX(CASE WHEN av.attribute_id = 2 THEN av.value END) AS size_name
        FROM cartitems ci
        INNER JOIN productvariants pv     ON pv.id = ci.variant_id
        INNER JOIN products p             ON p.id = pv.product_id
        LEFT JOIN productvariantvalues vv ON vv.variant_id = pv.id
        LEFT JOIN attributevalues av      ON av.id = vv.value_id
        WHERE ci.cart_id = :cart_id
        GROUP BY ci.variant_id, ci.quantity, p.name, p.image_thumbnail, pv.price, pv.sale_price
        ORDER BY ci.variant_id ASC
    ";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':cart_id', $cartId, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$r) {
            $r['quantity']           = (int)$r['quantity'];
            $r['original_price']     = (float)$r['original_price'];
            $r['sale_price']         = $r['sale_price'] !== null ? (float)$r['sale_price'] : null;
            $r['effective_price']    = (float)$r['effective_price'];
            $r['original_price_fmt'] = number_format($r['original_price'], 0, ',', '.') . 'đ';
            $r['sale_price_fmt']     = $r['sale_price'] !== null ? number_format($r['sale_price'], 0, ',', '.') . 'đ' : null;
            $r['effective_price_fmt'] = number_format($r['effective_price'], 0, ',', '.') . 'đ';
            $r['image_url']          = $r['image_url'] ?? '';
        }
        unset($r);

        return $rows;
    }



    // ===================================
    // TÍNH TỔNG: gốc, giảm giá, phải trả
    // ===================================
    public function calcCartTotals(array $items)
    {
        $totalOriginal = 0.0;  // Tổng giá gốc tất cả sp
        $totalDiscount = 0.0;  // Tổng phần giảm so với giá gốc (nếu có)

        foreach ($items as $it) {
            $qty    = (int)$it['quantity'];
            $priceO = (float)$it['original_price'];
            $priceS = $it['sale_price'] !== null ? (float)$it['sale_price'] : $priceO;

            $totalOriginal += $priceO * $qty;

            // giảm giá = (giá gốc - giá KM, nếu có và nhỏ hơn gốc) * qty
            $diff = $priceO - $priceS;
            if ($diff > 0) {
                $totalDiscount += $diff * $qty;
            }
        }

        $payable = $totalOriginal - $totalDiscount;

        return [
            'total_original'      => $totalOriginal,
            'total_discount'      => $totalDiscount,
            'total_payable'       => $payable,
            'total_original_fmt'  => number_format($totalOriginal, 0, ',', '.') . 'đ',
            'total_discount_fmt'  => number_format($totalDiscount, 0, ',', '.') . 'đ',
            'total_payable_fmt'   => number_format($payable, 0, ',', '.') . 'đ',
        ];
    }

    // Lấy họ tên, sđt, email theo user_id
    public function getCustomerBasic($userId)
    {
        $sql = "SELECT full_name AS name, phone_number AS phone, email
            FROM users
            WHERE id = :id
            LIMIT 1";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: ['name' => '', 'phone' => '', 'email' => ''];
    }


    // Lấy cart_id theo user (tạo nếu chưa có)
    private function getOrCreateCartId($userId)
    {
        $sql = "SELECT id FROM carts WHERE user_id = :user_id LIMIT 1";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $cartId = $stmt->fetchColumn();

        if ($cartId) return (int)$cartId;

        $sql = "INSERT INTO carts (user_id) VALUES (:user_id)";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return (int)$this->db->pdo->lastInsertId();
    }

    // Lấy giá bán thực tế của 1 variant (ưu tiên sale_price)
    private function getVariantPrice($variantId)
    {
        $sql = "SELECT COALESCE(sale_price, price) AS price FROM productvariants WHERE id = :variant_id LIMIT 1";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':variant_id', $variantId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (float)$row['price'] : null;
    }

    // Trừ tồn kho an toàn (fail nếu không đủ tồn)
    private function deductStock($variantId, $qty)
    {
        $sql = "UPDATE productvariants 
                SET quantity = quantity - :qty 
                WHERE id = :variant_id AND quantity >= :qty";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':qty', $qty, PDO::PARAM_INT);
        $stmt->bindParam(':variant_id', $variantId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0; // true nếu trừ được
    }

    // Hoàn tồn kho (dùng khi hủy đơn)
    private function restoreStock($variantId, $qty)
    {
        $sql = "UPDATE productvariants SET quantity = quantity + :qty WHERE id = :variant_id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':qty', $qty, PDO::PARAM_INT);
        $stmt->bindParam(':variant_id', $variantId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /* =======================
     * Cart queries
     * ======================= */

    // Lấy item trong giỏ + giá bán hiện tại
    public function getCartItemsWithPrice($userId)
    {
        $cartId = $this->getOrCreateCartId($userId);

        $sql = "SELECT ci.variant_id, ci.quantity,
                       COALESCE(pv.sale_price, pv.price) AS unit_price
                FROM cartitems ci
                INNER JOIN productvariants pv ON pv.id = ci.variant_id
                WHERE ci.cart_id = :cart_id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':cart_id', $cartId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // [ ['variant_id'=>..., 'quantity'=>..., 'unit_price'=>...], ... ]
    }

    // Xóa toàn bộ items của cart
    private function clearCart($userId)
    {
        $cartId = $this->getOrCreateCartId($userId);
        $sql = "DELETE FROM cartitems WHERE cart_id = :cart_id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':cart_id', $cartId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /* =======================
     * Orders (create/update/get)
     * ======================= */

    // Tạo đơn từ giỏ hàng của user
    public function createOrderFromCart($userId, $receiverName, $receiverPhone, $shippingAddress, $paymentMethod)
    {
        $items = $this->getCartItemsWithPrice($userId);
        if (empty($items)) {
            return ['success' => false, 'message' => 'Giỏ hàng trống.'];
        }

        try {
            $this->db->pdo->beginTransaction();

            // Trừ kho & tính tổng
            $total = 0.0;
            foreach ($items as $it) {
                $variantId = (int)$it['variant_id'];
                $qty       = (int)$it['quantity'];
                $price     = (float)$it['unit_price'];

                if (!$this->deductStock($variantId, $qty)) {
                    $this->db->pdo->rollBack();
                    return ['success' => false, 'message' => "Hết hàng hoặc tồn không đủ cho variant #$variantId."];
                }
                $total += $price * $qty;
            }

            // Tạo order
            $sql = "INSERT INTO orders (user_id, total_amount, receiver_name, receiver_phone, shipping_address, status, payment_method, payment_status)
                    VALUES (:user_id, :total_amount, :receiver_name, :receiver_phone, :shipping_address, 'pending', :payment_method, 'unpaid')";
            $stmt = $this->db->pdo->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':total_amount', $total);
            $stmt->bindParam(':receiver_name', $receiverName, PDO::PARAM_STR);
            $stmt->bindParam(':receiver_phone', $receiverPhone, PDO::PARAM_STR);
            $stmt->bindParam(':shipping_address', $shippingAddress, PDO::PARAM_STR);
            $stmt->bindParam(':payment_method', $paymentMethod, PDO::PARAM_STR);
            $stmt->execute();
            $orderId = (int)$this->db->pdo->lastInsertId();

            // Insert order items
            $sqlItem = "INSERT INTO orderitems (order_id, variant_id, quantity, price)
                        VALUES (:order_id, :variant_id, :quantity, :price)";
            $stmtItem = $this->db->pdo->prepare($sqlItem);

            foreach ($items as $it) {
                $variantId = (int)$it['variant_id'];
                $qty       = (int)$it['quantity'];
                $price     = (float)$it['unit_price'];

                // bindParam yêu cầu biến—không truyền trực tiếp literal
                $stmtItem->bindParam(':order_id', $orderId, PDO::PARAM_INT);
                $stmtItem->bindParam(':variant_id', $variantId, PDO::PARAM_INT);
                $stmtItem->bindParam(':quantity', $qty, PDO::PARAM_INT);
                $stmtItem->bindParam(':price', $price);
                $stmtItem->execute();
            }

            // Xóa giỏ
            $this->clearCart($userId);

            $this->db->pdo->commit();
            return ['success' => true, 'order_id' => $orderId, 'total' => $total];
        } catch (Exception $e) {
            if ($this->db->pdo->inTransaction()) {
                $this->db->pdo->rollBack();
            }
            return ['success' => false, 'message' => 'Lỗi tạo đơn: ' . $e->getMessage()];
        }
    }

    // Tạo đơn trực tiếp từ danh sách item (không qua giỏ)
    // $items = [ ['variant_id'=>101, 'quantity'=>2], ... ]
    public function createOrderDirect($userId, $items, $receiverName, $receiverPhone, $shippingAddress, $paymentMethod)
    {
        if (empty($items)) {
            return ['success' => false, 'message' => 'Danh sách sản phẩm rỗng.'];
        }

        try {
            $this->db->pdo->beginTransaction();

            $total = 0.0;
            $preparedItems = [];

            foreach ($items as $it) {
                $variantId = (int)$it['variant_id'];
                $qty       = max(1, (int)$it['quantity']);
                $price     = $this->getVariantPrice($variantId);

                if ($price === null) {
                    $this->db->pdo->rollBack();
                    return ['success' => false, 'message' => "Variant #$variantId không tồn tại."];
                }

                if (!$this->deductStock($variantId, $qty)) {
                    $this->db->pdo->rollBack();
                    return ['success' => false, 'message' => "Hết hàng hoặc tồn không đủ cho variant #$variantId."];
                }

                $total += $price * $qty;
                $preparedItems[] = ['variant_id' => $variantId, 'quantity' => $qty, 'price' => $price];
            }

            $sql = "INSERT INTO orders (user_id, total_amount, receiver_name, receiver_phone, shipping_address, status, payment_method, payment_status)
                    VALUES (:user_id, :total_amount, :receiver_name, :receiver_phone, :shipping_address, 'pending', :payment_method, 'unpaid')";
            $stmt = $this->db->pdo->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':total_amount', $total);
            $stmt->bindParam(':receiver_name', $receiverName, PDO::PARAM_STR);
            $stmt->bindParam(':receiver_phone', $receiverPhone, PDO::PARAM_STR);
            $stmt->bindParam(':shipping_address', $shippingAddress, PDO::PARAM_STR);
            $stmt->bindParam(':payment_method', $paymentMethod, PDO::PARAM_STR);
            $stmt->execute();
            $orderId = (int)$this->db->pdo->lastInsertId();

            $sqlItem = "INSERT INTO orderitems (order_id, variant_id, quantity, price)
                        VALUES (:order_id, :variant_id, :quantity, :price)";
            $stmtItem = $this->db->pdo->prepare($sqlItem);

            foreach ($preparedItems as $it) {
                $variantId = $it['variant_id'];
                $qty       = $it['quantity'];
                $price     = $it['price'];

                $stmtItem->bindParam(':order_id', $orderId, PDO::PARAM_INT);
                $stmtItem->bindParam(':variant_id', $variantId, PDO::PARAM_INT);
                $stmtItem->bindParam(':quantity', $qty, PDO::PARAM_INT);
                $stmtItem->bindParam(':price', $price);
                $stmtItem->execute();
            }

            $this->db->pdo->commit();
            return ['success' => true, 'order_id' => $orderId, 'total' => $total];
        } catch (Exception $e) {
            if ($this->db->pdo->inTransaction()) {
                $this->db->pdo->rollBack();
            }
            return ['success' => false, 'message' => 'Lỗi tạo đơn: ' . $e->getMessage()];
        }
    }

    // Đánh dấu thanh toán
    public function updatePaymentStatus($orderId, $paymentStatus) // 'paid' | 'unpaid'
    {
        $sql = "UPDATE orders SET payment_status = :payment_status WHERE id = :order_id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':payment_status', $paymentStatus, PDO::PARAM_STR);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Cập nhật trạng thái đơn
    public function updateOrderStatus($orderId, $status) // 'pending','processing','shipped','delivered','cancelled'
    {
        $sql = "UPDATE orders SET status = :status WHERE id = :order_id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Hủy đơn (chỉ khi chưa shipped/delivered), hoàn kho
    public function cancelOrder($orderId)
    {
        try {
            $this->db->pdo->beginTransaction();

            // Lấy trạng thái hiện tại
            $sql = "SELECT status FROM orders WHERE id = :order_id FOR UPDATE";
            $stmt = $this->db->pdo->prepare($sql);
            $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
            $stmt->execute();
            $status = $stmt->fetchColumn();

            if (!$status) {
                $this->db->pdo->rollBack();
                return ['success' => false, 'message' => 'Đơn không tồn tại.'];
            }
            if (in_array($status, ['shipped', 'delivered'], true)) {
                $this->db->pdo->rollBack();
                return ['success' => false, 'message' => 'Không thể hủy đơn đã gửi/hành thành.'];
            }

            // Hoàn kho theo orderitems
            $sql = "SELECT variant_id, quantity FROM orderitems WHERE order_id = :order_id";
            $stmt = $this->db->pdo->prepare($sql);
            $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
            $stmt->execute();
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($items as $it) {
                if ($it['variant_id'] !== null) {
                    $variantId = (int)$it['variant_id'];
                    $qty       = (int)$it['quantity'];
                    $this->restoreStock($variantId, $qty);
                }
            }

            // Cập nhật trạng thái
            $cancel = 'cancelled';
            $sql = "UPDATE orders SET status = :status WHERE id = :order_id";
            $stmt = $this->db->pdo->prepare($sql);
            $stmt->bindParam(':status', $cancel, PDO::PARAM_STR);
            $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
            $stmt->execute();

            $this->db->pdo->commit();
            return ['success' => true];
        } catch (Exception $e) {
            if ($this->db->pdo->inTransaction()) {
                $this->db->pdo->rollBack();
            }
            return ['success' => false, 'message' => 'Lỗi hủy đơn: ' . $e->getMessage()];
        }
    }

    // Lấy chi tiết đơn + items
    public function getOrderDetail($orderId)
    {
        $sql = "SELECT * FROM orders WHERE id = :order_id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) return null;

        $sql = "SELECT oi.variant_id, oi.quantity, oi.price
                FROM orderitems oi
                WHERE oi.order_id = :order_id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $order;
    }

    // Danh sách đơn theo user
    public function listOrdersByUser($userId, $limit = 50, $offset = 0)
    {
        $sql = "SELECT * FROM orders WHERE user_id = :user_id
                ORDER BY created_at DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        // Với LIMIT/OFFSET nên cast int và dùng PARAM_INT
        $limit = (int)$limit;
        $offset = (int)$offset;
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =======================
     * Wishlist (theo yêu cầu)
     * ======================= */

    public function addToWishlist($userId, $variantId)
    {
        $sql = "INSERT IGNORE INTO wishlistitems (user_id, variant_id) 
                VALUES (:user_id, :variant_id)";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':variant_id', $variantId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function removeFromWishlist($userId, $variantId)
    {
        $sql = "DELETE FROM wishlistitems WHERE user_id = :user_id AND variant_id = :variant_id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':variant_id', $variantId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getWishlist($userId)
    {
        $sql = "SELECT variant_id, created_at FROM wishlistitems WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ĐÁP ỨNG: orders.payment_status='paid', orders.status='processing'
    //          và orderitems.status='processing'
    // Chỉ cần 1 hàm này trong OrderModel
    public function finalizePaidOrder(int $orderId): bool
    {
        try {
            $this->db->pdo->beginTransaction();

            // 1) Cập nhật bảng orders
            $sql = "UPDATE orders
                SET payment_status = 'paid',
                    status = 'processing'
                WHERE id = :id";
            $stmt = $this->db->pdo->prepare($sql);
            $stmt->bindParam(':id', $orderId, PDO::PARAM_INT);
            $stmt->execute();
            $affectedOrders = $stmt->rowCount();

            // 2) Cập nhật orderitems (nếu có cột 'status').
            //    Nếu lỗi (không có cột/khác tên bảng), KHÔNG rollback.
            try {
                $sql2 = "UPDATE orderitems SET status = 'processing' WHERE order_id = :id";
                $stmt2 = $this->db->pdo->prepare($sql2);
                $stmt2->bindParam(':id', $orderId, PDO::PARAM_INT);
                $stmt2->execute();
            } catch (PDOException $e2) {
                error_log('[finalizePaidOrder][orderitems] ' . $e2->getMessage());
                // bỏ qua, vẫn commit nếu orders đã cập nhật
            }

            $this->db->pdo->commit();

            if ($affectedOrders === 0) {
                // Không có bản ghi nào bị ảnh hưởng -> sai ID?
                error_log("[finalizePaidOrder] No order updated for ID={$orderId}");
                return false;
            }
            return true;
        } catch (PDOException $e) {
            $this->db->pdo->rollBack();
            error_log('[finalizePaidOrder] ' . $e->getMessage());
            return false;
        }
    }
}
