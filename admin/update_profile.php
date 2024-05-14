<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_POST['submit'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);

   if (!empty($name)) {
      $sql="SELECT * FROM `admin` WHERE `name` ='$name' ";
      $select_name = $conn->query($sql);

      if ($select_name->num_rows > 0) {

         $message[] = 'Username already taken!';

      } else {
         $sql="UPDATE `admin` SET `name` = '$name' WHERE `id` = '$admin_id'";
         $update_name = $conn->query($sql);
      }
   }

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $sql="SELECT password FROM `admin` WHERE `id` = '$admin_id'";
   $select_old_pass = $conn->query($sql);

   $fetch_prev_pass = $select_old_pass->fetch_assoc();
   $prev_pass = $fetch_prev_pass['password'];
   $old_pass = sha1($_POST['old_pass']);
   $new_pass = sha1($_POST['new_pass']);
   $confirm_pass = sha1($_POST['confirm_pass']);

   if ($old_pass != $empty_pass) {
      if ($old_pass != $prev_pass) {
         $message[] = 'Old password not matched!';
      } elseif ($new_pass != $confirm_pass) {
         $message[] = 'Confirm password not matched!';
      } else {
         if ($new_pass != $empty_pass) {
            $sql="UPDATE `admin` SET `password` = '$confirm_pass' WHERE `id` = '$admin_id'";
            $update_pass =$conn->query($sql);
            
            $message[] = 'Password updated successfully!';
         } else {
            $message[] = 'Please enter a new password!';
         }
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
   <title>Profile Update</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- admin profile update section starts  -->

<section class="form-container">

   <form action="" method="POST">
      <h3>Update Profile</h3>
      <input type="text" name="name" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')" placeholder="<?= $fetch_profile['name']; ?>">
      <input type="password" name="old_pass" maxlength="20" placeholder="Enter your old password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="new_pass" maxlength="20" placeholder="Enter your new password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="confirm_pass" maxlength="20" placeholder="Confirm your new password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Update Now" name="submit" class="btn">
   </form>

</section>

<!-- admin profile update section ends -->

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>

