<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];

   $sql="DELETE FROM `users` WHERE `id` = '$delete_id'";
   $delete_users =$conn->query($sql);

   $sql="DELETE FROM `orders` WHERE `user_id` = '$delete_id'";
   $delete_users =$conn->query($sql);

   $sql="DELETE FROM `cart` WHERE `user_id` = '$delete_id'";
   $delete_users =$conn->query($sql);
   
   
   header('location:users_accounts.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Users Accounts</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- user accounts section starts  -->

<section class="accounts">

   <h1 class="heading">Users Account</h1>

   <div class="box-container">

   <?php
   $sql="SELECT * FROM `users`";
      $select_account =$conn->query($sql);
    
    if ($select_account->num_rows > 0) {
      while ($fetch_accounts = $select_account->fetch_assoc()) {
   ?>
   <div class="box">
      <p> User ID : <span><?= $fetch_accounts['id']; ?></span> </p>
      <p> Username : <span><?= $fetch_accounts['name']; ?></span> </p>
      <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('Delete this account?');">Delete</a>
   </div>
   <?php
         }
      } else {
         echo '<p class="empty">No accounts available</p>';
      }
   ?>

   </div>

</section>

<!-- user accounts section ends -->

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>

