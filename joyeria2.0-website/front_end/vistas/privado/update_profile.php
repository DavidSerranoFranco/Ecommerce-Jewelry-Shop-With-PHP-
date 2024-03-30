<?php

include '../../../back_end/base_datos/components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
   exit();
}

if (isset($_POST['submit'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   $update_profile_name = $conn->prepare("UPDATE `admins` SET name = ? WHERE id = ?");
   $update_profile_name->execute([$name, $admin_id]);

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $prev_pass = $_POST['prev_pass'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = sha1($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   if ($old_pass == $empty_pass) {
      $message[] = 'Por Favor Ingrese la Contraseña Anterior!';
   } elseif ($old_pass != $prev_pass) {
      $message[] = 'La Contraseña Anterior No Coincide!';
   } elseif ($new_pass != $confirm_pass) {
      $message[] = 'Confirmar Contraseña No Coincide!';
   } else {
      if ($new_pass != $empty_pass) {
         $update_admin_pass = $conn->prepare("UPDATE `admins` SET password = ? WHERE id = ?");
         $update_admin_pass->execute([$confirm_pass, $admin_id]);
         $message[] = 'Contraseña Actualizada Exitosamente!';
      } else {
         $message[] = 'Por Favor Ingrese Una Nueva Contraseña!';
      }
   }

   // Procesar la actualización de la imagen de perfil
   $image_column = 'image_admin'; // Cambiado para que coincida con el nombre de la columna en la base de datos

   $old_image = ''; // Variable para almacenar el nombre de la imagen anterior
   $image_admin = $_FILES['image_admin']['name'];
   $image_admin = filter_var($image_admin, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image_admin']['size'];
   $image_tmp_name = $_FILES['image_admin']['tmp_name'];
   $image_folder = '../../assets/uploaded_img/' . $image_admin;

   if (!empty($image_admin)) {
      if ($image_size > 2000000) {
         $message[] = '¡El tamaño de la imagen es demasiado grande!';
      } else {
         // Obtener la imagen anterior (si existe) para eliminarla después de la actualización
         $get_admin_image = $conn->prepare("SELECT $image_column FROM `admins` WHERE id = ?");
         $get_admin_image->execute([$admin_id]);
         $fetch_admin_image = $get_admin_image->fetch(PDO::FETCH_ASSOC);
         if ($fetch_admin_image && !empty($fetch_admin_image[$image_column])) {
            $old_image = $fetch_admin_image[$image_column];
         }

         $update_image = $conn->prepare("UPDATE `admins` SET $image_column = ? WHERE id = ?");
         $update_image->execute([$image_admin, $admin_id]);
         move_uploaded_file($image_tmp_name, $image_folder);

         // Eliminar la imagen anterior solo si existe y no es la imagen por defecto
         if (!empty($old_image) && $old_image != 'default.jpg' && file_exists('../../assets/uploaded_img/' . $old_image)) {
            unlink('../../assets/uploaded_img/' . $old_image);
         }

         $message[] = '¡Imagen del Perfil actualizada exitosamente!';
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
   <title>Bijoux - Actualización del Perfil</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../../assets/css/css_admin.css">

   <!-- custom icon file link  -->
   <link rel="icon" href="../../assets/images/logo-bijoux1.ico">

</head>
<body>

<?php include '../../../back_end/base_datos/components/admin_header.php'; ?>

<section class="form-container">

   <form action="" method="post" enctype="multipart/form-data">
      <h3>actualización del perfil</h3>
      <input type="hidden" name="prev_pass" value="<?= $fetch_profile['password']; ?>">
      <input type="text" name="name" value="<?= $fetch_profile['name']; ?>" required placeholder="Ingrese su Nombre de Usuario" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="old_pass" placeholder="Ingrese la Contraseña Anterior" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="new_pass" placeholder="Introduzca Nueva Contraseña" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="confirm_pass" placeholder="Confirmar Nueva Contraseña" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <h1>Actualizar Foto de Perfil</h1>
      <input type="file" name="image_admin" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
      <input type="submit" value="actualizar ahora" class="btn" name="submit">
   </form>

</section>

<script src="../../assets/js/admin_script.js"></script>
   
</body>
</html>
