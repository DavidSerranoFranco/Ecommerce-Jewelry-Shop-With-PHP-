<?php

include '../../../back_end/base_datos/components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_admins = $conn->prepare("DELETE FROM `admins` WHERE id = ?");
   $delete_admins->execute([$delete_id]);
   header('location:admin_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bijoux - Cuentas de Administrador</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../../assets/css/css_admin.css">

   <!-- custom icon file link  -->
   <link rel="icon" href="../../assets/images/logo-bijoux1.ico">

</head>
<body>

<?php include '../../../back_end/base_datos/components/admin_header.php'; ?>

<section class="accounts">

   <h1 class="heading">cuentas de administrador</h1>

   <div class="box-container">

   <div class="box">
      <p>añadir nuevo administrador</p>
      <a href="register_admin.php" class="option-btn">registrar administrador</a>
   </div>

   <?php
      $select_accounts = $conn->prepare("SELECT * FROM `admins`");
      $select_accounts->execute();
      if ($select_accounts->rowCount() > 0) {
         while ($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
   ?>
   <div class="box">
      <p> admin id : <span><?= $fetch_accounts['id']; ?></span> </p>
      <p> admin nombre : <span><?= $fetch_accounts['name']; ?></span> </p>
      <p class="user-profile-pic"> Foto de Perfil : 
         <?php
         if (!empty($fetch_accounts['image_admin'])) {
            echo '<img src="../../assets/uploaded_img/' . $fetch_accounts['image_admin'] . '" alt="Foto de Perfil" style="max-width: 100px;">';
         } else {
            echo 'No hay foto de perfil';
         }
         ?>
      </p>
      <div class="flex-btn">
         <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('Desea Eliminar Esta Cuenta?')" class="delete-btn">eliminar</a>
         <?php
         if ($fetch_accounts['id'] == $admin_id) {
            echo '<a href="update_profile.php" class="option-btn">Actualizar</a>';
         }
         ?>
      </div>
   </div>
   <?php
         }
      } else {
         echo '<p class="empty">no accounts available!</p>';
      }
   ?>

   </div>

</section>

<script src="../../assets/js/admin_script.js"></script>
   
</body>
</html>