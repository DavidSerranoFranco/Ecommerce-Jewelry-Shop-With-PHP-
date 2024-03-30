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

   <section class="flex">

      <a href="../../../front_end/vistas/privado/dashboard.php" class="logo">Admin<span>Panel</span></a>

      <nav class="navbar">
         <a href="../../../front_end/vistas/privado/dashboard.php">Inicio</a>
         <a href="../../../front_end/vistas/privado/products.php">Productos</a>
         <a href="../../../front_end/vistas/privado/placed_orders.php">Ordenes</a>
         <a href="../../../front_end/vistas/privado/admin_accounts.php">Admins</a>
         <a href="../../../front_end/vistas/privado/users_accounts.php">Usuarios</a>
         <a href="../../../front_end/vistas/privado/messages.php">Mensajes</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <!-- Mostrar la foto de perfil del usuario -->
         <div  class="user-profile-pic" id="user-btn">
      <?php
         $select = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
         $select->execute([$admin_id]);
         $fetch = $select->fetch(PDO::FETCH_ASSOC);

         if (empty($fetch['image_admin'])) {
            echo '<img src="../../../front_end/assets/images/default-perfil.png" alt="User Profile Picture" width="30px">';
         } else {
            echo '<img src="../../../front_end/assets/uploaded_img/'.$fetch['image_admin'].'" alt="User Profile Picture" width="50px">';
         }
      ?>
   </div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p><?= $fetch_profile['name']; ?></p>
         <a href="../../../front_end/vistas/privado/update_profile.php" class="btn">actualización del perfil</a>
         <div class="flex-btn">
            <a href="../../../front_end/vistas/privado/register_admin.php" class="option-btn">registrar</a>
            <a href="../../../front_end/vistas/privado/admin_login.php" class="option-btn">iniciar sesion</a>
         </div>
         <a href="../../../back_end/base_datos/components/admin_logout.php" class="delete-btn" onclick="return confirm('Desea Cerrar Sesión en el Sitio Web?');">cerrar sesion</a> 
      </div>

   </section>

</header>