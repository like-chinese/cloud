<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"><img src="project images/delete.png" style="height:43px;"></i>
      </div>
      ';
   }
}
?>


<header class="header">

   <section class="flex">

      <a href="index.php" class="logo">TAR UMT</a>

      <nav class="navbar">
         <a href="index.php">Home</a>
         <a href="about.php">about</a>
         <a href="menu.php">menu</a>
         <a href="orders.php">orders</a>
         <a href="contact.php">contact</a>
      </nav>

      <div class="icons" style="width:120px;height:30px;display:inline;">
         <?php
        //    $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        //    $count_cart_items->execute([$user_id]);
        //    $total_cart_items = $count_cart_items->rowCount();

	$count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->bind_param("i", $user_id);
            $count_cart_items->execute();

 $count_cart_items->store_result();
            $total_cart_items = $count_cart_items->num_rows;
         ?>
         <div style="display:inline;width:150px;">
            <a href="search.php" style="width:16px;"><i  class="fas fa-search" style="width:16px;"><img src="project images/search.png" style="width:16px;"></i></a>
            <a href="cart.php" style="width:16px;"><i class="fas fa-shopping-cart" style="width:16px;"><img src="project images/shopping-cart.png" style="width:16px;"></i><span>(<?= $total_cart_items; ?>)</span></a>
            <div id="user-btn" class="fas fa-user" style="width:16px;display: inline-block;"><img src="project images/user.png" style="width:16px;"></div>
            <div id="menu-btn" class="fas fa-bars" style="width:16px;"><img src="project images/menu.png" style="width:16px;"></div>
         </div>
      </div>


      <div class="profile">
         <?php
//            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
  //          $select_profile->execute([$user_id]);
$select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select_profile->bind_param("i", $user_id);
$select_profile->execute();
     $result = $select_profile->get_result();

            if($result->num_rows > 0){
               $fetch_profile = $result->fetch_assoc();

     //   if($select_profile->rowCount() > 0){
       //       $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
     
 ?>
         <p class="name"><?= $fetch_profile['name']; ?></p>
         <div class="flex">
            <a href="profile.php" class="btn">profile</a>
            <a href="components/user_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
         </div>
         <p class="account">
            <a href="login.php">login</a> or
            <a href="register.php">register</a>
         </p> 
         <?php
            }else{
         ?>
            <p class="name">please login first!</p>
            <a href="login.php" class="btn">login</a>
         <?php
          }
         ?>
      </div>

   </section>

</header>

