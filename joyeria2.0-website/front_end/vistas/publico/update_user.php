<?php

include '../../../back_end/base_datos/components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];

   // Obtener detalles del perfil del usuario
   $get_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
   $get_profile->execute([$user_id]);
   $fetch_profile = $get_profile->fetch(PDO::FETCH_ASSOC);

} else {
   $user_id = '';
   $fetch_profile = [];
}

if (isset($_POST['submit'])) {

   // Resto del código de actualización del perfil (nombre, correo, contraseña)
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);


   // Actualización de nombre y correo electrónico
   $update_profile = $conn->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
   $update_profile->execute([$name, $email, $user_id]);


   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $prev_pass = $_POST['prev_pass'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);


   if ($old_pass == $empty_pass) {
      $message[] = 'Por Favor Ingrese la Contraseña Anterior!';
   } elseif ($old_pass != $prev_pass) {
      $message[] = 'Contraseña Anterior no Coincide!';
   } elseif ($new_pass != $cpass) {
      $message[] = 'Confirmar Contraseña no Coincide!';
   } else {
      if ($new_pass != $empty_pass) {
         $update_admin_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
         $update_admin_pass->execute([$cpass, $user_id]);
         $message[] = 'Contraseña Actualizada Exitosamente!';
      } else {
         $message[] = 'Por Favor Ingrese una Nueva Contraseña!';
      }
   }


   // Actualización de la imagen de perfil
   $old_image = $fetch_profile['image_user'];
   $image_user = $_FILES['image_user']['name'];
   $image_user = filter_var($image_user, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image_user']['size'];
   $image_tmp_name = $_FILES['image_user']['tmp_name'];
   $image_folder = '../../assets/uploaded_img/' . $image_user;

   if (!empty($image_user)) {
      if ($image_size > 2000000) {
         $message[] = '¡El tamaño de la imagen es demasiado grande!';
      } else {
         $update_image = $conn->prepare("UPDATE `users` SET image_user = ? WHERE id = ?");
         $update_image->execute([$image_user, $user_id]);
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
   <title>Bijoux - Actualizar Usuario</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../../assets/css/home.css">

   <!-- custom icon file link  -->
   <link rel="icon" href="../../assets/images/logo-bijoux1.ico">

</head>

<body>

   <?php include '../../../back_end/base_datos/components/user_header.php'; ?>

   <section class="form-container">

      <form action="" method="post" enctype="multipart/form-data">
         <h3>actualizar ahora</h3>
         <input type="hidden" name="prev_pass" value="<?= $fetch_profile["password"]; ?>">
         <input type="text" name="name" required placeholder="Ingrese su Nombre de Usuario" maxlength="20" class="box" value="<?= $fetch_profile["name"]; ?>">
         <input type="email" name="email" required placeholder="Introduce tu Correo Electrónico" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')" value="<?= $fetch_profile["email"]; ?>">
         <input type="password" name="old_pass" placeholder="Ingrese su Antigua Contraseña" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="new_pass" placeholder="Introduzca su Nueva Contraseña" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="cpass" placeholder="Confirma tu Nueva Contraseña" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <h2>Actualizar Foto de Perfil</h2>
         <div>
            <input type="file" name="image_user" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
         </div>
         
         <input type="submit" value="Actualizar Ahora" class="btn" name="submit">
      </form>

   </section>

   <?php include '../../../back_end/base_datos/components/footer.php'; ?>

   <script src="../../assets/js/script.js"></script>

</body>

</html>