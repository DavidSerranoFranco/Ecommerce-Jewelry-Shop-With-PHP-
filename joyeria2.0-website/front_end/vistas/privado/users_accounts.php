<?php

include '../../../back_end/base_datos/components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_user = $conn->prepare("DELETE FROM `users` WHERE id = ?");
   $delete_user->execute([$delete_id]);
   $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
   $delete_orders->execute([$delete_id]);
   $delete_messages = $conn->prepare("DELETE FROM `messages` WHERE user_id = ?");
   $delete_messages->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:users_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bijoux - Cuentas de Usuarios</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../../assets/css/css_admin.css">

   <!-- custom icon file link  -->
   <link rel="icon" href="../../assets/images/logo-bijoux1.ico">

</head>
<body>

<?php include '../../../back_end/base_datos/components/admin_header.php'; ?>

<section class="accounts">

   <h1 class="heading">cuentas de usuarios</h1>

   <div class="box-container">

   <?php
      $select_accounts = $conn->prepare("SELECT * FROM `users`");
      $select_accounts->execute();
      if ($select_accounts->rowCount() > 0) {
         while ($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
   ?>
   <div class="box">
      <p> id : <span><?= $fetch_accounts['id']; ?></span> </p>
      <p> usuario : <span><?= $fetch_accounts['name']; ?></span> </p>
      <p> email : <span><?= $fetch_accounts['email']; ?></span> </p>
      <p class="user-profile-pic"> Foto de Perfil : 
         <?php
         if (!empty($fetch_accounts['image_user'])) {
            echo '<img src="../../assets/uploaded_img/' . $fetch_accounts['image_user'] . '" alt="Foto de Perfil" style="max-width: 100px;">';
         } else {
            echo 'No hay imagen de perfil';
         }
         ?>
      </p>
      <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('delete this account? the user related information will also be delete!')" class="delete-btn">eliminar</a>
   </div>
   <?php
         }
      } else {
         echo '<p class="empty">No Hay Cuentas Disponibles!</p>';
      }
   ?>

   </div>

</section>

<script src="../../assets/js/admin_script.js"></script>
   
</body>
</html>