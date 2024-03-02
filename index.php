<?php
    require_once('./model.php');
    session_start();
    if(!isset($_SESSION['cart'])){
        $_SESSION['cart'] = [];
    }
    
    if(isset($_POST['addToCart'])){
        $id = $_POST['product_id'];
        $quantity = 1;
        $price = $_POST['product_price'];
        $flag = 0;
        for($i=0; $i < sizeof($_SESSION['cart']);$i++){
            if ($id == $_SESSION['cart'][$i][0]){//[0] là phần tử đầu tiên của mục hiện tại trong giỏ hàng, tương ứng lưu id sp
                $flag = 1;
                $new_quantity = $_SESSION['cart'][$i][1] + 1;//[1] là phần tử thứ 2 của mục hiện tại trong giỏ hàng, tương ứng lưu số lượng sp
                $_SESSION['cart'][$i][1] = $new_quantity;
                break;
            } 
        }

        if($flag == 0){
            $product = [$id, $quantity, $price];
            $_SESSION['cart'][] = $product;//thêm phần tử mới vào cuối mảng
        }
    }

    // remove item
    if(isset($_GET['delid']) && $_GET['delid'] >= 0){
        array_splice($_SESSION['cart'], $_GET['delid'], 1);//xóa 1 phần tử tại index = $_GET['delid']
    }

    // decrease item
    if(isset($_GET['subItemId']) && $_GET['subItemId'] >= 0){//$_GET['subItemId'] là index lần lượt của từng sp đã thêm vào $_SESSION['cart']
        if ($_SESSION['cart'][$_GET['subItemId']][1] > 1){
            $new_quantity = $_SESSION['cart'][$_GET['subItemId']][1] - 1;
            $_SESSION['cart'][$_GET['subItemId']][1] = $new_quantity;
        } else{
            array_splice($_SESSION['cart'], $_GET['subItemId'], 1);
        }
    }

    // increase item
    if(isset($_GET['addItemId']) && $_GET['addItemId'] >= 0){
        $new_quantity = $_SESSION['cart'][$_GET['addItemId']][1] + 1;
        $_SESSION['cart'][$_GET['addItemId']][1] = $new_quantity;
    }

    //Our products
    function showProducts(){
        $products = getData();
                foreach($products as $product){
                    echo '<div class="product_item">
                        <div class="product-itemImage" style="background-color: '.$product['color'].'">
                            <img src="'.$product['image'].'" 
                            alt="" class="product-itemImage_img">
                        </div>
                        <div class="product_itemName">'.$product['name'].'</div>
                        <div class="product_itemDescription">
                        '.$product['description'].'
                        </div>
                        <div class="product_itemBottom">
                            <div class="product_itemPrice">$'.$product['price'].'</div>
                            <form action = "index.php" method="post">
                                <div class="product_itemButton">
                                    <button type="submit" name="addToCart">ADD TO CART</button>
                                    <input type="hidden" name="product_id" value='.$product['id'].'>
                                    <input type="hidden" name="product_image" value='.$product['image'].'>
                                    <input type="hidden" name="product_name" value='.$product['name'].'>
                                    <input type="hidden" name="product_price" value='.$product['price'].'>
                                    <input type="hidden" name="product_color" value='.$product['color'].'>
                                </div>
                            </form>
                        </div>
                    </div>'; 
                }
    }

    //Your cart
    function showCartItem(){
        if(isset($_SESSION['cart']) && sizeof($_SESSION['cart']) > 0){
            for($i = 0; $i < sizeof($_SESSION['cart']); $i++){
                $result = getProduct($_SESSION['cart'][$i][0]);
                $pro = $result->fetch_array();
                echo '<div class="cart-item">
                <div class="cart-itemLeft">
                    <div class="cart-itemImage" style="background-color: '.$pro['color'].'">
                        <div class="cart-itemImageBlock">
                            <img src="'.$pro['image'].'" 
                            alt="" class="cart-itemImage_img">
                        </div>
                    </div>
                </div>
                <div class="cart-itemRight">
                    <div class="cart-itemName">'.$pro['name'].'</div>
                    <div class="cart-itemPrice">$'.$pro['price'].'</div>
                    <div class="cart-itemActions">
                        <div class="cart-itemCount">
                            <a href="index.php?subItemId='.$i.'" class="cart-itemCountButton" id="increase" name="minus">-</a>
                            <span class="cart-itemCountQuantity" style="width: 20px; text-align: center;">'.$_SESSION['cart'][$i][1].'</span>
                            <a href="index.php?addItemId='.$i.'" class="cart-itemCountButton" id="decrease" name="add">+</a>
                        </div>
                        <a href="index.php?delid='.$i.'" class="cart-itemCountRemove">
                            <img src="./app/assets/trash.png" alt="" class="cart-itemCountRemove_img">
                        </a>
                    </div>
                </div>
            </div>';
            }
        }
        else{
            echo '<h5>Your cart is empty</h5>';
        }
    }

    function total(){
        $total = 0;
        if(isset($_SESSION['cart']) && sizeof($_SESSION['cart']) > 0){
            for($i = 0; $i < sizeof($_SESSION['cart']); $i++){
                $total += $_SESSION['cart'][$i][2] * $_SESSION['cart'][$i][1];
            } 
        }
        return $total;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./styles.css">
    <link rel="stylesheet" href="https://fonts.google.com/specimen/Rubik?query=Rubik">
</head>

<body>
    <div class="container">
        <div class="container-item">
            <div class="cart-top">
                <img src="./app/assets/nike.png" alt="" class="cart-top_logo">
            </div>
            <div class="cart-title">Our Products</div>
            <div class="cart-body">
                <?php
                showProducts();
                ?>
            </div>

        </div>

        <div class="container-item">
            <div class="cart-top">
                <img src="./app/assets/nike.png" alt="" class="cart-top_logo">
            </div>
            <div class="cart-title" style="display: flex; justify-content: space-between">
                <span>Your Cart</span>
                <?php echo '<span name="total" style="display: right">$'.total().'</span>';?>
            </div>
            <div class="cart-body">
                <?php
                showCartItem();
                ?>
            </div>
        </div>
    </div>
</body>
</html>