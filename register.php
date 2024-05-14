<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'components/connect.php';

session_start();

$message = []; // Initialize $message as an array

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){
   $id = $_POST['id'];
   $name = $_POST['name'];
   $email = $_POST['email'];
   $number = $_POST['number'];
   $pass = sha1($_POST['pass']);
   $cpass = sha1($_POST['cpass']);

   $sql="SELECT * FROM `users` WHERE  `id` = '$id'";
   $select_user =  $conn->query($sql);

   
   if ($select_user->num_rows > 0) {
      $message[] = 'The id already exists!'; // Push message into the array
  }else{
      if($pass != $cpass){
          $message[] = 'Confirm password not matched!'; // Push message into the array
      }else{
         $sql="INSERT INTO `users`(`id`, `name`, `email`, `number`, `password`) VALUES('$id', '$name', '$email', '$number', '$cpass')";
         $insert_user = $conn->query($sql);

         $sql="SELECT * FROM `users` WHERE `email` = '$email' AND `password` = '$pass'";
         $select_user =  $conn->query($sql);
        
        
         if ($select_user->num_rows > 0) {
    $row = $select_user->fetch_assoc();
    $_SESSION['user_id'] = $row['id']; // Get the id from the fetched row
    header('location:index.php');
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
   <title>register</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
   body {
        background-image: url("images/background.png");
        background-size: cover; /* Cover the entire area */
        background-position: center; /* Center the background image */
    }
</style> 

</head>
<body>
   
<!-- header section starts  -->

<!-- header section ends -->

<section class="form-container">

   <form action="" method="post">
      <h3>register now</h3>
      <input type="text" name="id" required placeholder="enter your id" class="box" maxlength="50">
      <input type="text" name="name" required placeholder="enter your name" class="box" maxlength="50">
      <input type="email" name="email" required placeholder="enter your email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="number" name="number" required placeholder="enter your number" class="box" min="1" max="9999999999" maxlength="10">
      <input type="password" name="pass" required placeholder="enter your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="confirm your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="register now" name="submit" class="btn">
      <p><?php foreach ($message as $msg) { echo $msg . '<br>'; } ?></p> <!-- Display each message in the array -->
      <p>already have an account? <a href="login.php">login now</a></p>
   </form>

</section>

</body>
</html>

