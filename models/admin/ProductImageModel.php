<?php
class ProductImageModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Lấy tất cả ảnh sản phẩm với thông tin variant và product
    public function getAllImages()
    {
        $sql = "SELECT 
                    pi.id,
                    pi.variant_id,
                    pi.image_url,
                    pi.created_at, 
                    pv.product_id,
                    p.name as product_name,
                    pv.price,
                    pv.sale_price
                FROM productimages pi
                LEFT JOIN productvariants pv ON pi.variant_id = pv.id
                LEFT JOIN products p ON pv.product_id = p.id
                ORDER BY pi.id DESC";

        $stmt = $this->db->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy ảnh theo variant_id
    public function getImagesByVariantId($variant_id)
    {
        $sql = "SELECT * FROM productimages WHERE variant_id = :variant_id ORDER BY id ASC";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':variant_id', $variant_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy ảnh theo product_id
    public function getImagesByProductId($product_id)
    {
        $sql = "SELECT pi.*, pv.product_id 
                FROM productimages pi
                LEFT JOIN productvariants pv ON pi.variant_id = pv.id
                WHERE pv.product_id = :product_id
                ORDER BY pi.id ASC";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm ảnh mới
    public function addImage($variant_id, $image_url)
    {
        $sql = "INSERT INTO productimages (variant_id, image_url) VALUES (:variant_id, :image_url)";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':variant_id', $variant_id, PDO::PARAM_INT);
        $stmt->bindParam(':image_url', $image_url, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Thêm nhiều ảnh cùng lúc
    public function addMultipleImages($variant_id, $image_urls)
    {
        try {
            $this->db->pdo->beginTransaction();

            $sql = "INSERT INTO productimages (variant_id, image_url) VALUES (:variant_id, :image_url)";
            $stmt = $this->db->pdo->prepare($sql);

            foreach ($image_urls as $image_url) {
                $stmt->bindParam(':variant_id', $variant_id, PDO::PARAM_INT);
                $stmt->bindParam(':image_url', $image_url, PDO::PARAM_STR);
                $stmt->execute();
            }

            $this->db->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->db->pdo->rollBack();
            return false;
        }
    }

    // Cập nhật ảnh
    public function updateImage($id, $image_url)
    {
        $sql = "UPDATE productimages SET image_url = :image_url WHERE id = :id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':image_url', $image_url, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Xóa ảnh theo ID
    public function deleteImage($id)
    {
        // Lấy thông tin ảnh trước khi xóa để xóa file vật lý
        $image = $this->getImageById($id);

        $sql = "DELETE FROM productimages WHERE id = :id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Xóa file vật lý nếu tồn tại
            if ($image && file_exists($image['image_url'])) {
                unlink($image['image_url']);
            }
            return true;
        }
        return false;
    }

    // Xóa tất cả ảnh của một variant
    public function deleteImagesByVariantId($variant_id)
    {
        // Lấy danh sách ảnh để xóa file vật lý
        $images = $this->getImagesByVariantId($variant_id);

        $sql = "DELETE FROM productimages WHERE variant_id = :variant_id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':variant_id', $variant_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Xóa các file vật lý
            foreach ($images as $image) {
                if (file_exists($image['image_url'])) {
                    unlink($image['image_url']);
                }
            }
            return true;
        }
        return false;
    }

    // Lấy một ảnh theo ID
    public function getImageById($id)
    {
        $sql = "SELECT * FROM productimages WHERE id = :id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Đếm số lượng ảnh của một variant
    public function countImagesByVariantId($variant_id)
    {
        $sql = "SELECT COUNT(*) as total FROM productimages WHERE variant_id = :variant_id";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':variant_id', $variant_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Lấy ảnh đại diện (ảnh đầu tiên) của variant
    public function getMainImageByVariantId($variant_id)
    {
        $sql = "SELECT * FROM productimages WHERE variant_id = :variant_id ORDER BY id ASC LIMIT 1";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':variant_id', $variant_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tìm kiếm ảnh theo tên file
    public function searchImagesByName($search_term)
    {
        $sql = "SELECT pi.*, pv.product_id, p.name as product_name
                FROM productimages pi
                LEFT JOIN productvariants pv ON pi.variant_id = pv.id
                LEFT JOIN products p ON pv.product_id = p.id
                WHERE pi.image_url LIKE :search_term
                ORDER BY pi.id DESC";

        $stmt = $this->db->pdo->prepare($sql);
        $search_param = '%' . $search_term . '%';
        $stmt->bindParam(':search_term', $search_param, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
