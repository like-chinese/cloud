<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'components/connect.php';

$message ='';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){
   $id = $_POST['id'];
   $pass = sha1($_POST['pass']);

   // Prepare SQL statement with placeholders
   $sql = "SELECT * FROM `users` WHERE `id` = ? AND `password` = ?";
   $select_user = $conn->prepare($sql);
   $select_user->bind_param("ss", $id, $pass); // Bind parameters
   $select_user->execute();
   $result_user = $select_user->get_result(); // Get the result set

   if ($result_user->num_rows > 0) {
      $row = $result_user->fetch_assoc(); // Fetch the result as an associative array
      $_SESSION['user_id'] = $row['id'];
      header('location:index.php');
      exit; // Add exit to prevent further execution
   } else {
      // Close previous statement result
      $select_user->free_result();

      // For admin login
      $sql = "SELECT * FROM `admin` WHERE `id` = ? AND `password` = ?";
      $select_admin = $conn->prepare($sql);
      $select_admin->bind_param("ss", $id, $pass); // Bind parameters
      $select_admin->execute();
      $result_admin = $select_admin->get_result(); // Get the result set

      if ($result_admin->num_rows > 0) {
         $row_admin = $result_admin->fetch_assoc();
         $_SESSION['admin_id'] = $row_admin['id'];
         header('location:admin/dashboard.php');
         exit; // Add exit to prevent further execution
      } else {
         $message = 'Incorrect username or password!';
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
   <title>Login</title>
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
   <section class="form-container">
      <form action="" method="post">
         <h3>Login now</h3>
         <input type="id" name="id" required placeholder="Enter your ID" class="box" maxlength="50">
         <input type="password" name="pass" required placeholder="Enter your password" class="box" maxlength="50">
         <input type="submit" value="Login now" name="submit" class="btn">
         <p><?php echo $message; ?></p> <!-- Display error message -->
         <p>Don't have an account? <a href="register.php">Register now</a></p>
      </form>
   </section>
   <script src="js/script.js"></script>
</body>
</html>

