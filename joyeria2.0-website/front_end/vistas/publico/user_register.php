<?php

include '../../../back_end/base_datos/components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if (isset($_POST['submit'])) {
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   // Obtener la información de la imagen del usuario
   $image_user = $_FILES['image_user']['name'];
   $image_tmp_name_user = $_FILES['image_user']['tmp_name'];
   $image_size_user = $_FILES['image_user']['size'];

   // Ruta donde se guardará la imagen del usuario en el servidor
   $image_folder_user = '../../assets/uploaded_img/' . $image_user;

   // Verificar si se subió una imagen y si el tamaño es adecuado
   if ($image_user && $image_size_user > 0 && $image_size_user <= 2000000) {
      // Mover la imagen a la carpeta de destino en el servidor
      move_uploaded_file($image_tmp_name_user, $image_folder_user);

      // Resto del código para verificar si el correo ya existe, insertar en la base de datos, etc.
      $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $select_user->execute([$email]);
      $row = $select_user->fetch(PDO::FETCH_ASSOC);

      if ($select_user->rowCount() > 0) {
         $message[] = 'El Email Ya Existe!';
      } else {
         if ($pass != $cpass) {
            $message[] = 'Confirmar Contraseña No Coincide!';
         } else {
            $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password, image_user) VALUES(?,?,?,?)");
            $insert_user->execute([$name, $email, $cpass, $image_user]);
            $message[] = 'Registrado(a) con éxito, Inicie Sesión Ahora Por Favor!';
         }
      }
   } else {
      // Si la imagen es demasiado grande o no se ha subido una imagen, mostrar un mensaje de error
      $message[] = 'Por favor, sube una imagen válida (tamaño máximo: 2 MB).';
   }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bijoux - Registro</title>
   
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
      <h3>Registrar Ahora</h3>
      <input type="text" name="name" required placeholder="Escriba un Usuario" maxlength="20"  class="box">
      <input type="email" name="email" required placeholder="Ingrese su Email" maxlength="50"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Escriba una Contraseña" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Confirme la Contraseña" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <h2>Foto de Perfil</h2>
		<input type="file" name="image_user" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
      <input type="submit" value="Registrar Ahora" class="btn" name="submit">
      <p>¿Ya tienes una cuenta?</p>
      <a href="user_login.php" class="option-btn">Iniciar Sesion</a>
   </form>

</section>

<?php include '../../../back_end/base_datos/components/footer.php'; ?>

<script src="../../assets/js/script.js"></script>

</body>
</html>