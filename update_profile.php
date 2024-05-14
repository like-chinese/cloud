<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:login.php');  
   exit; 
}

if(isset($_POST['submit'])){
   $name = $_POST['name'];
   $email = $_POST['email'];
   $number = $_POST['number'];
   $old_pass = $_POST['old_pass'];
   $new_pass = $_POST['new_pass'];
   $confirm_pass = $_POST['confirm_pass'];

   // Update name
   if(!empty($name)){
      $sql="UPDATE `users` SET `name` = '$name' WHERE `id` = '$user_id'";
      $update_name =$conn->query($sql);
   }

   // Update email
   if(!empty($email)){
      $sql="UPDATE `users` SET `email` = '$email' WHERE `id` = '$user_id'";
      $update_email =$conn->query($sql);
   }

   // Update number
   if(!empty($number)){
      $sql="UPDATE `users` SET `number` = '$number' WHERE `id` = '$user_id'";
      $update_number =$conn->query($sql);
     
   }

   // Update password
   if(!empty($old_pass) && !empty($new_pass) && !empty($confirm_pass)){
      $sql="SELECT `password` FROM `users`  WHERE `id` = '$user_id'";
      $select_prev_pass=$conn->query($sql);
     
      $fetch_prev_pass = $select_prev_pass->fetch(PDO::FETCH_ASSOC);
      $prev_pass = $fetch_prev_pass['password'];

      if(sha1($old_pass) != $prev_pass){
         $message[] = 'Old password not matched!';
      }elseif($new_pass != $confirm_pass){
         $message[] = 'Confirm password not matched!';
      }else{
         $new_pass=sha1($new_pass);
         $sql="UPDATE `users` SET `password` = '$new_pass' WHERE `id` = '$user_id'";
         $update_number =$conn->query($sql);
         $message[] = 'Password updated successfully!';
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
   <title>Update Profile</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="form-container update-form">
   <form action="" method="post">
      <h3>Update Profile</h3>
      <input type="text" name="name" placeholder="Name" class="box" maxlength="50" value="<?= $fetch_profile['name']; ?>">
      <input type="email" name="email" placeholder="Email" class="box" maxlength="50" value="<?= $fetch_profile['email']; ?>">
      <input type="number" name="number" placeholder="Phone Number" class="box" min="1" max="9999999999" maxlength="10" value="<?= $fetch_profile['number']; ?>">
      <input type="password" name="old_pass" placeholder="Enter Old Password" class="box" maxlength="50">
      <input type="password" name="new_pass" placeholder="Enter New Password" class="box" maxlength="50">
      <input type="password" name="confirm_pass" placeholder="Confirm New Password" class="box" maxlength="50">
      <input type="submit" value="Update Now" name="submit" class="btn">
   </form>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
</body>
</html>

