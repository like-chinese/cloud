<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:../login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashboard</title>

   <!-- font awesome cdn link 
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    -->

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- admin dashboard section starts  -->

<section class="dashboard">

   <h1 class="heading">dashboard</h1>

 <div class="box-container">

   <div class="box">
      <h3>welcome!</h3>
      <p><?= $fetch_profile['name']; ?></p>
      <a href="update_profile.php" class="btn">update profile</a>
   </div>

<div class="box">
    <?php
    $total_pendings = 0;
    $status_pending = 'pending'; // Set the status value
    $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
    $select_pendings->bind_param("s", $status_pending); // Bind the parameter
    $select_pendings->execute();
    $result = $select_pendings->get_result(); // Get the result set

    while ($fetch_pendings = $result->fetch_assoc()) {
        $total_pendings += $fetch_pendings['total_price'];
    }
    ?>
    <h3><span>$</span><?php echo $total_pendings; ?><span>/-</span></h3>
    <p>total pendings</p>
    <a href="placed_orders.php" class="btn">see orders</a>
</div>


   <div class="box">
      <?php
      $sql="SELECT * FROM `products`";
         $select_products =  $conn->query($sql);
         
         $numbers_of_products = $select_products->num_rows;
      ?>
      <h3><?= $numbers_of_products; ?></h3>
      <p>products added</p>
      <a href="products.php" class="btn">see products</a>
   </div>

   <div class="box">
      <?php
      $sql="SELECT * FROM `users`";
         $select_users = $conn->query($sql);
     
         $numbers_of_users = $select_users->num_rows;
      ?>
      <h3><?= $numbers_of_users; ?></h3>
      <p>users accounts</p>
      <a href="users_accounts.php" class="btn">see users</a>
   </div>

   <div class="box">
      <?php
      $sql="SELECT * FROM `admin`";
         $select_admins = $conn->query($sql);
       
         $numbers_of_admins = $select_admins->num_rows;
      ?>
      <h3><?= $numbers_of_admins; ?></h3>
      <p>admins</p>
      <a href="admin_accounts.php" class="btn">see admins</a>
   </div>

   <div class="box">
      <?php
      $sql="SELECT * FROM `messages`"; // Corrected variable name
         $select_messages =$conn->query($sql);
 
         $numbers_of_messages = $select_messages->num_rows;
      ?>
      <h3><?= $numbers_of_messages; ?></h3>
      <p>new messages</p>
      <a href="messages.php" class="btn">see messages</a>
   </div>

   </div>

</section>

<!-- admin dashboard section ends -->









<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>

