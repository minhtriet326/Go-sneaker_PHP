<?php
    function connectDatabase() {
        $servername = "localhost:3307";
        $username = "root";
        $password = "";
        $database = "go_sneaker";
        
        // Create connection to database
        $conn = mysqli_connect($servername, $username, $password, $database);
        
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        return $conn;
    }

    //Display products
    function getData($pids = []){//$pids là mảng chứa ID sản phẩm
        $where = "";
        if(count($pids)) {
            $pids = implode(",", $pids);//chuyển đổi $pids thành chuỗi các ID được phân cách bằng dấu phẩy
            $where = " where id in $pids";
        }

        $query = "SELECT * FROM shoes $where";

        $conn = connectDatabase();//đối tượng kết nối
        $result = $conn->query($query);

        if($result->num_rows > 0){//kt số lượng row trả về
            return $result;
        }
    }

    //Display products in the cart
    function getProduct($id){
        $query = "SELECT * FROM shoes where id = $id";
        $conn = connectDatabase();
        $result = $conn->query($query);

        if($result->num_rows > 0){
            return $result;
        }
    }
?>