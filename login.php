<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $id = $_POST['id'];
   $id = filter_var($id, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);                           //sha1 是用来加密用的
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ? AND password = ?");
   $select_user->execute([$id, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $_SESSION['user_id'] = $row['id'];
      header('location:index.php');
   }else{
      // 如果用户不在 users 表中，则检查是否是管理员
      $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ? AND password = ?");
      $select_admin->execute([$id, $pass]);
      $row = $select_admin->fetch(PDO::FETCH_ASSOC);
      if($select_admin->rowCount() > 0){
         $_SESSION['admin_id'] = $row['id'];
         header('location:admin/dashboard.php');
      } else {
         // 如果既不是用户也不是管理员，则显示错误消息
         $message[] = 'incorrect username or password!';
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
   <title>login</title>

   
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
<!-- <?php include 'components/user_header.php'; ?>  -->
<!-- header section ends -->


<section class="form-container">

   <form action="" method="post">
      <h3>login now</h3>
      <input type="id" name="id" required placeholder="enter your ID" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="enter your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="login now" name="submit" class="btn">
      <p>don't have an account? <a href="register.php">register now</a></p>
   </form>

</section>










<!--
<?php include 'components/footer.php'; ?>
-->





<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>