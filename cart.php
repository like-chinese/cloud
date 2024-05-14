<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'components/connect.php';

session_start();

$user_id = '';
$message = []; // Removed unnecessary square brackets

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:login.php');
   exit; // Added exit after header redirect
} 

if(isset($_POST['delete'])){
   $cart_id = $_POST['cart_id'];

   $sql="DELETE FROM `cart` WHERE `id` ='$cart_id' "; // Replaced single quotes with backticks

   $delete_cart_item = $conn->query($sql);
   
   if($delete_cart_item === TRUE){ // Corrected variable name
      $message[] = 'Cart item deleted!';
   }else{
      $message[] = 'Error deleting cart item!';
   }
}

if(isset($_POST['delete_all'])){
   $sql="DELETE FROM `cart` WHERE `user_id` ='$user_id' ";
   $delete_cart_item = $conn->query($sql);
   
   if($delete_cart_item === TRUE){ // Corrected variable name
      $message[] = 'Deleted all items from cart!';
   }else{
      $message[] = 'Error deleting all items from cart!';
   }
}

if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $qty = intval($_POST['qty']); 
   if($qty > 0){
      $sql="UPDATE `cart` SET `quantity` ='$qty' WHERE `id` = '$cart_id'"; // Replaced single quotes with backticks
      $update_qty = $conn->query($sql); 
     
      if ($update_qty === TRUE) { // Corrected variable name
         $message[] = 'Cart quantity updated';
      }else{
         $message[] = 'Error updating cart quantity!';
      }
   }else{
      $message[] = 'Error preparing update quantity statement!';
   }
}

$grand_total = 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>cart</title>

   <!-- font awesome cdn link  
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   -->

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>shopping cart</h3>
   <p><a href="index.php">Home</a> <span> / cart</span></p>
</div>

<!-- shopping cart section starts  -->

<section class="products">

   <h1 class="title">your cart</h1>

   <div class="box-container">

<?php
$grand_total = 0;
$select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
$select_cart->bind_param("s", $user_id);
$select_cart->execute();
$result = $select_cart->get_result();  // Get the result object from the executed statement

if ($result->num_rows > 0) {
    while ($fetch_cart = $result->fetch_assoc()) {  // Fetch each row as an associative array
?>

      <form action="" method="post" class="box">
         <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
         <a href="quick_view.php?pid=<?= $fetch_cart['pid']; ?>"  class="fas fa-eye"><img src="project images/eye.png" style="height:43px;"></a>
         <button type="submit" class="fas fa-times" name="delete" onclick="return confirm('delete this item?');"><img src="project images/delete.png" style="height:43px;"></button>
         <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
         <div class="name"><?= $fetch_cart['name']; ?></div>
         <div class="flex">
            <div class="price"><span>$</span><?= $fetch_cart['price']; ?></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="<?= $fetch_cart['quantity']; ?>" maxlength="2">
            <button type="submit" class="fas fa-edit" name="update_qty"><img src="project images/edit.png" style="height:45px;"></button>
         </div>
         <div class="sub-total"> sub total : <span>$<?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</span> </div>
      </form>
      <?php
               $grand_total += $sub_total;
            }
         }else{
            echo '<p class="empty">your cart is empty</p>';
         }
      ?>

   </div>

   <div class="cart-total">
      <p>cart total : <span>$<?= $grand_total; ?></span></p>
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">proceed to checkout</a>
   </div>

   <div class="more-btn">
      <form action="" method="post">
         <button type="submit" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" name="delete_all" onclick="return confirm('delete all from cart?');">delete all</button>
      </form>
      <a href="menu.php" class="btn">continue shopping</a>
   </div>

</section>

<!-- shopping cart section ends -->










<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->








<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
