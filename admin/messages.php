<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit(); // Ensure script stops executing after redirection
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    $sql="DELETE FROM `messages` WHERE id = '$delete_id'";
    $delete_message =  $conn->query($sql);

    header('location:messages.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>messages</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- messages section starts  -->

<section class="messages">

   <h1 class="heading">messages</h1>

   <div class="box-container">

   <?php
      $sql="SELECT * FROM `messages`";
      $select_messages = $conn->query($sql);
      
      if ($select_messages->num_rows > 0) {
         while ($fetch_messages = $select_messages->fetch_assoc()) {
   ?>
   <div class="box">
      <p> name : <span><?= $fetch_messages['name']; ?></span> </p>
      <p> number : <span><?= $fetch_messages['number']; ?></span> </p>
      <p> email : <span><?= $fetch_messages['email']; ?></span> </p>
      <p> message : <span><?= $fetch_messages['message']; ?></span> </p>
      <a href="messages.php?delete=<?= $fetch_messages['id']; ?>" class="delete-btn" onclick="return confirm('delete this message?');">delete</a>
   </div>
   <?php
         }
      } else {
         echo '<p class="empty">you have no messages</p>';
      }
   ?>

   </div>

</section>

<!-- messages section ends -->

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>

