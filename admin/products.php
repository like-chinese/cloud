<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];
$category = isset($_GET['category']) ? $_GET['category'] : null;

if (!isset($admin_id)) {
   header('location:admin_login.php');
   exit(); // Ensure script stops executing after redirection
}

if (isset($_POST['add_product'])) {

   $name = $_POST['name'];
   $price = $_POST['price'];
   $category = $_POST['category'];


   $image = $_FILES['image']['name'];
  
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;


   $sql="SELECT * FROM `products` WHERE name = '$name'";
   $select_products = $conn->query($sql);
  
   
   if ($select_products->num_rows > 0) {
      $message[] = 'Product name already exists!';
   } else {
      if ($image_size > 2000000) {
         $message[] = 'Image size is too large';
      } else {
         move_uploaded_file($image_tmp_name, $image_folder);


         $sql="INSERT INTO `products`(`name`, `category`, `price`, `image`) VALUES('$name','$category','$price','$image')";
         $insert_product = $conn->query($sql);
      

         $message[] = 'New product added!';
      }

   }

}











if (isset($_GET['delete'])) {

   $delete_id = $_GET['delete'];

   // Select product image
   $sql = "SELECT * FROM `products` WHERE `id` = ?";
   $select_product_image = $conn->prepare($sql);
   $select_product_image->bind_param('i', $delete_id);
   $select_product_image->execute();
   $delete_product_image = $select_product_image->get_result();
   
   $fetch_delete_image = $delete_product_image->fetch_assoc();
   unlink('../uploaded_img/'.$fetch_delete_image['image']);

   // Delete product
   $sql = "DELETE FROM `products` WHERE `id` = ?";
   $delete_product = $conn->prepare($sql);
   $delete_product->bind_param('i', $delete_id);
   $delete_product->execute();

   // Delete related cart items
   $sql = "DELETE FROM `cart` WHERE `pid` = ?";
   $delete_cart = $conn->prepare($sql);
   $delete_cart->bind_param('i', $delete_id);
   $delete_cart->execute();
   
   header('location:products.php');

}



?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">
   <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- add products section starts  -->

<section class="add-products">

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>add product</h3>
      <input type="text" required placeholder="enter product name" name="name" maxlength="100" class="box">
      <input type="number" min="1" max="9999999999" required placeholder="enter product price" name="price" onkeypress="if(this.value.length == 10) return false;" class="box">
      <select name="category" class="box" required>
         <option value="" disabled selected>select category --</option>
         <option value="flower">Flower</option>
         <option value="balloon">Balloon</option>
         <option value="bear">Bear</option>
         <option value="others">Others</option>
      </select>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
      <input type="submit" value="add product" name="add_product" class="btn">
   </form>

</section>

<!-- add products section ends -->

<!-- Show the category -->
<section class="category">
   <h1 class="title">Product Categories</h1>
   <div class="box-container">
      <a href="products.php?category=flower" class="box">
         <img src="../images/flower.png" alt="">
         <h3>Flower</h3>
      </a>
      <a href="products.php?category=balloon" class="box">
         <img src="../images/balloon.png" alt="">
         <h3>Balloon</h3>
      </a>
      <a href="products.php?category=bear" class="box">
         <img src="../images/bear.png" alt="">
         <h3>Bear</h3>
      </a>
      <a href="products.php?category=others" class="box">
         <img src="../images/cat-4.png" alt="">
         <h3>Others</h3>
      </a>
   </div>
</section>

<!-- Show the ends part of the category -->

<!-- show products section starts  -->

<section class="show-products" style="padding-top: 0;">

   <div class="box-container">

   <?php
  
      $category_clause = ($category) ? "WHERE category = '$category'" : ""; // 构建分类条件
      $sql = "SELECT * FROM `products` $category_clause"; // 构建完整的 SQL 查询
      
      $show_products = $conn->query($sql); // 执行查询
      
      // 处理结果
      if ($show_products->num_rows > 0) {
       //  while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {  
	while ($fetch_products = $show_products->fetch_assoc()) {  
 ?>
   <div class="box">
      <img src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="flex">
         <div class="price"><span>$</span><?= $fetch_products['price']; ?><span>/-</span></div>
         <div class="category"><?= $fetch_products['category']; ?></div>
      </div>
      <div class="name"><?= $fetch_products['name']; ?></div>
      <div class="flex-btn">
         <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
         <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
      </div>
   </div>
   <?php
         }
      } else {
         echo '<p class="empty">no products added yet!</p>';
      }
   ?>

   </div>

</section>

<!-- show products section ends -->

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>

