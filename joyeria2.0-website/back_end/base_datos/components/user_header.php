<?php
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<header class="header">
   <link rel="stylesheet" href="../../../front_end/assets/css/3css.css">

   <section class="flex">

      <a href="home.php"><img src="../../../front_end/assets/images/logo-bijoux.png" alt="Bijoux" width="100"></a>
      
      <nav class="navbar">
         <a href="home.php">Inicio</a>
         <a href="about.php">Nosotros</a>
         <a href="orders.php">Orden</a>
         <a href="shop.php">Tienda</a>
         <a href="contact.php">Contacto</a>
      </nav>

      <div class="icons">
         <?php
            $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
            $count_wishlist_items->execute([$user_id]);
            $total_wishlist_counts = $count_wishlist_items->rowCount();

            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_counts = $count_cart_items->rowCount();
         ?>
         <div id="menu-btn" class="fas fa-bars"></div>
         <a href="search_page.php"><i class="fas fa-search"></i></a>
         <a href="wishlist.php"><i class="fas fa-heart"></i><span>(<?= $total_wishlist_counts; ?>)</span></a>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_counts; ?>)</span></a>
         
         <!-- Mostrar la foto de perfil del usuario -->
         <div  class="user-profile-pic" id="user-btn">
      <?php
         $select = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
         $select->execute([$user_id]);
         $fetch = $select->fetch(PDO::FETCH_ASSOC);

         if (empty($fetch['image_user'])) {
            echo '<img src="../../../front_end/assets/images/default-perfil.png" alt="User Profile Picture" width="30px">';
         } else {
            echo '<img src="../../../front_end/assets/uploaded_img/'.$fetch['image_user'].'" alt="User Profile Picture" >';
         }
      ?>
   </div>
      </div>

      <div class="profile">
         <?php          
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p><?= $fetch_profile["name"]; ?></p>
         <a href="update_user.php" class="btn">actualización del perfil</a>
         <div class="flex-btn">
            <a href="user_register.php" class="option-btn">registrar</a>
            <a href="user_login.php" class="option-btn">iniciar sesion</a>
         </div>
         <a href="../../../back_end/base_datos/components/user_logout.php" class="delete-btn" onclick="return confirm('Desea Cerrar Sesión en el Sitio Web?');">cerrar sesion</a> 
         <?php
            }else{
         ?>
         <p>Por Favor Inicie Sesion o Registrese Primero!</p>
         <div class="flex-btn">
            <a href="user_register.php" class="option-btn">registrar</a>
            <a href="user_login.php" class="option-btn">iniciar sesion</a>
         </div>
         <?php
            }
         ?>      
         
         
      </div>

   </section>

</header>