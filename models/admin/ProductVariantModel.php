<?php
class ProductVariantModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Lấy tất cả variants
    public function getAllVariants()
    {
        $sql = "SELECT pv.*, p.name as product_name, c.name as category_name
                FROM productvariants pv
                LEFT JOIN products p ON pv.product_id = p.id
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY pv.created_at DESC";
        
        $stmt = $this->db->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy variant theo ID
    public function getVariantById($id)
    {
        $sql = "SELECT pv.*, p.name as product_name, c.name as category_name
                FROM productvariants pv
                LEFT JOIN products p ON pv.product_id = p.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE pv.id = :id";
        
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy variants theo product_id
    public function getVariantsByProductId($product_id)
    {
        $sql = "SELECT pv.*, 
                       GROUP_CONCAT(CONCAT(a.name, ': ', av.value) SEPARATOR ', ') as attributes
                FROM productvariants pv
                LEFT JOIN productvariantvalues pvv ON pv.id = pvv.variant_id
                LEFT JOIN attributevalues av ON pvv.value_id = av.id
                LEFT JOIN attributes a ON av.attribute_id = a.id
                WHERE pv.product_id = :product_id
                GROUP BY pv.id
                ORDER BY pv.created_at ASC";
        
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm variant mới
    public function addVariant($product_id, $price, $sale_price, $quantity, $image_url = null)
    {
        $sql = "INSERT INTO productvariants (product_id, price, sale_price, quantity, image_url) 
                VALUES (:product_id, :price, :sale_price, :quantity, :image_url)";
        
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':sale_price', $sale_price, PDO::PARAM_STR);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':image_url', $image_url, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            return $this->db->pdo->lastInsertId();
        }
        return false;
    }

    // Cập nhật variant
    public function updateVariant($id, $price, $sale_price, $quantity, $image_url = null)
    {
        if ($image_url !== null) {
            $sql = "UPDATE productvariants 
                    SET price = :price, sale_price = :sale_price, quantity = :quantity, 
                        image_url = :image_url, updated_at = CURRENT_TIMESTAMP
                    WHERE id = :id";
        } else {
            $sql = "UPDATE productvariants 
                    SET price = :price, sale_price = :sale_price, quantity = :quantity,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = :id";
        }
        
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':sale_price', $sale_price, PDO::PARAM_STR);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        
        if ($image_url !== null) {
            $stmt->bindParam(':image_url', $image_url, PDO::PARAM_STR);
        }
        
        return $stmt->execute();
    }

    // Xóa variant
    public function deleteVariant($id)
    {
        try {
            $this->db->pdo->beginTransaction();
            
            // Xóa ảnh của variant
            $sql1 = "DELETE FROM productimages WHERE variant_id = :id";
            $stmt1 = $this->db->pdo->prepare($sql1);
            $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt1->execute();
            
            // Xóa attribute values của variant
            $sql2 = "DELETE FROM productvariantvalues WHERE variant_id = :id";
            $stmt2 = $this->db->pdo->prepare($sql2);
            $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt2->execute();
            
            // Xóa variant
            $sql3 = "DELETE FROM productvariants WHERE id = :id";
            $stmt3 = $this->db->pdo->prepare($sql3);
            $stmt3->bindParam(':id', $id, PDO::PARAM_INT);
            $result = $stmt3->execute();
            
            $this->db->pdo->commit();
            return $result;
            
        } catch (Exception $e) {
            $this->db->pdo->rollBack();
            return false;
        }
    }

    // Lấy variant với attributes chi tiết
    public function getVariantWithAttributes($id)
    {
        $sql = "SELECT pv.*, p.name as product_name,
                       GROUP_CONCAT(CONCAT(a.name, ': ', av.value) SEPARATOR ', ') as attributes
                FROM productvariants pv
                LEFT JOIN products p ON pv.product_id = p.id
                LEFT JOIN productvariantvalues pvv ON pv.id = pvv.variant_id
                LEFT JOIN attributevalues av ON pvv.value_id = av.id
                LEFT JOIN attributes a ON av.attribute_id = a.id
                WHERE pv.id = :id
                GROUP BY pv.id";
        
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật số lượng tồn kho
    public function updateQuantity($id, $quantity)
    {
        $sql = "UPDATE productvariants 
                SET quantity = :quantity, updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";
        
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    // Giảm số lượng tồn kho (khi có đơn hàng)
    public function decreaseQuantity($id, $amount)
    {
        $sql = "UPDATE productvariants 
                SET quantity = quantity - :amount, updated_at = CURRENT_TIMESTAMP
                WHERE id = :id AND quantity >= :amount";
        
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    // Tăng số lượng tồn kho (khi hủy đơn hàng)
    public function increaseQuantity($id, $amount)
    {
        $sql = "UPDATE productvariants 
                SET quantity = quantity + :amount, updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";
        
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    // Đếm tổng số variants
    public function countVariants()
    {
        $sql = "SELECT COUNT(*) as total FROM productvariants";
        $stmt = $this->db->pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Lấy variants có sale_price (đang giảm giá)
    public function getVariantsOnSale()
    {
        $sql = "SELECT pv.*, p.name as product_name
                FROM productvariants pv
                LEFT JOIN products p ON pv.product_id = p.id
                WHERE pv.sale_price IS NOT NULL AND pv.sale_price > 0
                ORDER BY ((pv.price - pv.sale_price) / pv.price) DESC";
        
        $stmt = $this->db->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy variants sắp hết hàng
    public function getLowStockVariants($threshold = 10)
    {
        $sql = "SELECT pv.*, p.name as product_name
                FROM productvariants pv
                LEFT JOIN products p ON pv.product_id = p.id
                WHERE pv.quantity <= :threshold AND pv.quantity > 0
                ORDER BY pv.quantity ASC";
        
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':threshold', $threshold, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm attribute values cho variant
    public function addVariantAttributes($variant_id, $attribute_value_ids)
    {
        try {
            $this->db->pdo->beginTransaction();
            
            // Xóa attributes cũ
            $sql_delete = "DELETE FROM productvariantvalues WHERE variant_id = :variant_id";
            $stmt_delete = $this->db->pdo->prepare($sql_delete);
            $stmt_delete->bindParam(':variant_id', $variant_id, PDO::PARAM_INT);
            $stmt_delete->execute();
            
            // Thêm attributes mới
            $sql_insert = "INSERT INTO productvariantvalues (variant_id, value_id) VALUES (:variant_id, :value_id)";
            $stmt_insert = $this->db->pdo->prepare($sql_insert);
            
            foreach ($attribute_value_ids as $value_id) {
                $stmt_insert->bindParam(':variant_id', $variant_id, PDO::PARAM_INT);
                $stmt_insert->bindParam(':value_id', $value_id, PDO::PARAM_INT);
                $stmt_insert->execute();
            }
            
            $this->db->pdo->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->pdo->rollBack();
            return false;
        }
    }
}