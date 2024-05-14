<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
if(isset($_POST['add_to_cart'])){

   if($user_id == ''){
      header('location:login.php');
   }else{

      $pid = $_POST['pid'];
     
      $name = $_POST['name'];
     
      $price = $_POST['price'];
    
      $image = $_POST['image'];
      
      $qty = $_POST['qty'];
    

      $sql="SELECT * FROM `cart` WHERE `name` = '$name' AND `user_id` = '$user_id'";
      $check_cart_numbers =$conn->query($sql);

      if($check_cart_numbers->num_rows > 0){
         $message[] = 'already added to cart!';
      }else{

         $sql="INSERT INTO `cart`(`user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES('$user_id', '$pid', '$name',' $price', '$qty', '$image')";
         $insert_cart = $conn->query($sql);
         $message[] = 'added to cart!';
         
      }

   }

}

?>
