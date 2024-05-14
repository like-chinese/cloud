<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_POST['update'])) {

   $pid = $_POST['pid'];
   $name = $_POST['name'];
   $price = $_POST['price'];
   $category = $_POST['category'];

   $sql="UPDATE `products` SET `name` = '$name', `category` = '$category', `price` = '$price' WHERE `id` = '$pid'";

   $update_product =$conn->query($sql);

   $message[] = 'Product updated!';

   if (!empty($_FILES['image']['name'])) {
      $old_image = $_POST['old_image'];
      $image = $_FILES['image']['name'];
      $image_size = $_FILES['image']['size'];
      $image_tmp_name = $_FILES['image']['tmp_name'];
      $image_folder = '../uploaded_img/' . $image;

      if ($image_size > 2000000) {
         $message[] = 'Image size is too large!';
      } else {
         $sql="UPDATE `products` SET image = $image WHERE id = $pid";
         $update_image = $conn->query($sql);
         move_uploaded_file($image_tmp_name, $image_folder);
         unlink('../uploaded_img/' . $old_image);
         $message[] = 'Image updated!';
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update product</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- update product section starts  -->

<section class="update-product">

   <h1 class="heading">Update Product</h1>

   <?php
      $update_id = $_GET['update'];
      $show_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $show_products->bind_param('i', $update_id);
      $show_products->execute();
      $result = $show_products->get_result();
      if($result->num_rows > 0){
         while($fetch_products = $result->fetch_assoc()){  
   ?>
   <form action="" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="old_image" value="<?= $fetch_products['image']; ?>">
      <img src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <span>Update Name</span>
      <input type="text" required placeholder="Enter product name" name="name" maxlength="100" class="box" value="<?= $fetch_products['name']; ?>">
      <span>Update Price</span>
      <input type="number" min="1" max="9999999999" required placeholder="Enter product price" name="price" onkeypress="if(this.value.length == 10) return false;" class="box" value="<?= $fetch_products['price']; ?>">
      <span>Update Category</span>
      <select name="category" class="box" required>
         <option selected value="<?= $fetch_products['category']; ?>"><?= $fetch_products['category']; ?></option>
         <option value="main dish">Main Dish</option>
         <option value="fast Graduate Product">Fast Graduate Product</option>
         <option value="drinks">Drinks</option>
         <option value="desserts">Desserts</option>
      </select>
      <span>Update Image</span>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      <div class="flex-btn">
         <input type="submit" value="Update" class="btn" name="update">
         <a href="products.php" class="option-btn">Go Back</a>
      </div>
   </form>
   <?php
         }
      } else {
         echo '<p class="empty">No products added yet!</p>';
      }
   ?>

</section>

<!-- update product section ends -->

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>

