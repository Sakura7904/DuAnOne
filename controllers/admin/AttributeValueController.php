<?php
include_once "models/admin/AttributeValueModel.php";

class AttributeValueController
{
    /** @var AttributeValueModel */
    private $model;

    /** Dùng tạm để lấy danh sách attributes cho dropdown */
    private $db;

    public function __construct()
    {
        $this->model = new AttributeValueModel();
        $this->db    = new Database(); // $this->db->pdo là PDO
    }

    /* =============== LIST =============== */
    public function index()
    {
        $page     = max(1, (int)($_GET['page'] ?? 1));
        $perPage  = max(1, min(100, (int)($_GET['per_page'] ?? 20)));
        $filters  = [
            'attribute_id' => isset($_GET['attribute_id']) ? (int)$_GET['attribute_id'] : null,
            'q'            => trim($_GET['q'] ?? ''),
        ];
        $sort = $_GET['sort'] ?? 'av.id DESC';

        $result     = $this->model->list($filters, $page, $perPage, $sort);
        $attributes = $this->getAllAttributes();

        $content = getContentPath('Attribute_Value', 'attributeList');
        view('admin/master', [
            'content'     => $content,
            'items'       => $result['data'],
            'pagination'  => $result['pagination'],
            'filters'     => $filters,
            'sort'        => $sort,
            'attributes'  => $attributes,
        ]);
    }

    /* =============== CREATE (FORM) =============== */
    public function create()
    {
        $attributes = $this->getAllAttributes();

        // lấy old input + errors nếu có
        $old    = $_SESSION['old_attribute_value'] ?? [];
        $errors = $_SESSION['errors_attribute_value'] ?? [];
        unset($_SESSION['old_attribute_value'], $_SESSION['errors_attribute_value']);

        $content = getContentPath('Attribute_Value', 'attributeCreate');
        view('admin/master', [
            'content'    => $content,
            'attributes' => $attributes,
            'old'        => $old,
            'errors'     => $errors,
        ]);
    }

    /* =============== STORE (POST) =============== */
    public function store()
    {
        $data = [
            'attribute_id' => (int)($_POST['attribute_id'] ?? 0),
            'value'        => trim($_POST['value'] ?? ''),
            'color_code'   => trim($_POST['color_code'] ?? ''),
        ];

        try {
            $id = $this->model->create($data);

            $_SESSION['alert'] = [
                'type'    => 'success',
                'message' => 'Thêm giá trị thuộc tính thành công.',
            ];
            $this->redirect('?admin=attribute_values'); // về list
        } catch (InvalidArgumentException $e) {
            // validate fail
            $_SESSION['errors_attribute_value'] = ['form' => $e->getMessage()];
            $_SESSION['old_attribute_value']    = $data;

            $_SESSION['alert'] = [
                'type'    => 'error',
                'message' => $e->getMessage(),
            ];
            $this->redirect('?admin=attribute_values_create'); // về form create
        } catch (Throwable $e) {
            // lỗi không mong muốn
            $_SESSION['errors_attribute_value'] = ['form' => 'Có lỗi xảy ra. Vui lòng thử lại.'];
            $_SESSION['old_attribute_value']    = $data;

            $_SESSION['alert'] = [
                'type'    => 'error',
                'message' => 'Không thể thêm giá trị thuộc tính.',
            ];
            $this->redirect('?admin=attribute_values_create');
        }
    }

    /* =============== EDIT (FORM) =============== */
    public function edit()
    {
        $id = (int)($_GET['id'] ?? 0);
        $item = $this->model->find($id);
        if (!$item) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Không tìm thấy bản ghi.'];
            $this->redirect('?admin=attribute_values');
        }

        $attributes = $this->getAllAttributes();
        $old    = $_SESSION['old_attribute_value'] ?? [];
        $errors = $_SESSION['errors_attribute_value'] ?? [];
        unset($_SESSION['old_attribute_value'], $_SESSION['errors_attribute_value']);

        $content = getContentPath('Attribute_Value', 'attributeEdit');
        view('admin/master', [
            'content'    => $content,
            'item'       => $item,
            'attributes' => $attributes,
            'old'        => $old,
            'errors'     => $errors,
        ]);
    }

    /* =============== UPDATE (POST) =============== */
    public function update()
    {
        // id có thể lấy từ hidden input hoặc query tuỳ bạn
        $id = (int)($_POST['id'] ?? ($_GET['id'] ?? 0));

        $data = [
            'attribute_id' => (int)($_POST['attribute_id'] ?? 0),
            'value'        => trim($_POST['value'] ?? ''),
            'color_code'   => trim($_POST['color_code'] ?? ''),
        ];

        try {
            $ok = $this->model->update($id, $data);

            $_SESSION['alert'] = [
                'type'    => 'success',
                'message' => 'Cập nhật thành công.',
            ];
            $this->redirect('?admin=attribute_values');
        } catch (InvalidArgumentException $e) {
            $_SESSION['errors_attribute_value'] = ['form' => $e->getMessage()];
            $_SESSION['old_attribute_value']    = array_merge($data, ['id' => $id]);

            $_SESSION['alert'] = [
                'type'    => 'error',
                'message' => $e->getMessage(),
            ];
            $this->redirect('?admin=attribute_values_edit&id=' . $id);
        } catch (Throwable $e) {
            $_SESSION['errors_attribute_value'] = ['form' => 'Có lỗi xảy ra. Vui lòng thử lại.'];
            $_SESSION['old_attribute_value']    = array_merge($data, ['id' => $id]);

            $_SESSION['alert'] = [
                'type'    => 'error',
                'message' => 'Không thể cập nhật bản ghi.',
            ];
            $this->redirect('?admin=attribute_values_edit&id=' . $id);
        }
    }

    /* =============== DELETE (POST/GET) =============== */
    public function destroy()
    {
        $id = (int)($_POST['id'] ?? ($_GET['id'] ?? 0));

        try {
            $this->model->delete($id);
            $_SESSION['alert'] = [
                'type'    => 'success',
                'message' => 'Đã xoá giá trị thuộc tính.',
            ];
        } catch (Throwable $e) {
            $_SESSION['alert'] = [
                'type'    => 'error',
                'message' => 'Không thể xoá bản ghi này.',
            ];
        }
        $this->redirect('?admin=attribute_values');
    }

    /* =============== HELPERS =============== */

    private function redirect(string $url)
    {
        header("Location: $url");
        exit;
    }

    /** Lấy danh sách attributes cho select (id, name) */
    private function getAllAttributes(): array
    {
        $sql = "SELECT id, name FROM attributes ORDER BY name ASC";
        $stmt = $this->db->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
