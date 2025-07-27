<?php
class Database{
    public $pdo;
    public function __construct(){
        $host = 'localhost';
        $db_name = 'duan-one';
        $user = 'root';
        $password = '';
        $port = '3306';
        
        $dsn = "mysql:host=$host;dbname=$db_name;port=$port;charset=UTF8";
        try{
            $this->pdo = new PDO($dsn, $user, $password);
            if($this->pdo){
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
                // echo "Kết nối thành công";
            }
        }catch(PDOException $e){
            echo $e->getMessage();
            echo"Lỗi";
        }
    }
}

function view($view, $data = [])
{
    //hàm extract để tạo các biến la key theo mảng liên kết như sau:
    //$data=['id'=>1, 'name'=>'nguyễn văn a'] thì khi sử dụng hàm extract sẽ được biến $id=1 và biến $name='nguyễn văn a'
    extract($data);
    include_once "views/$view.php";
}
function upload_file($file)
{
    if ($file['size'] > 0) {
        $newImg = time() . '_' . $file['name'];
        $hinh_sp = "images/" . $newImg;
        move_uploaded_file($file['tmp_name'], $hinh_sp);
        return $hinh_sp;
    }
    return "";
}