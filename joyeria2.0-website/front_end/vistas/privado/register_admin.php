<?php
include '../../../back_end/base_datos/components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   // Obtener la información de la imagen del administrador
   $image_admin = $_FILES['image_admin']['name'];
   $image_tmp_name_admin = $_FILES['image_admin']['tmp_name'];
   $image_size_admin = $_FILES['image_admin']['size'];

   // Ruta donde se guardará la imagen del administrador en el servidor
   $image_folder_admin = '../../assets/uploaded_img/' . $image_admin;

   // Verificar si se subió una imagen y si el tamaño es adecuado
   if ($image_admin && $image_size_admin > 0 && $image_size_admin <= 2000000) {
      // Mover la imagen a la carpeta de destino en el servidor
      move_uploaded_file($image_tmp_name_admin, $image_folder_admin);

      $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ?");
      $select_admin->execute([$name]);

      if($select_admin->rowCount() > 0){
         $message[] = 'El Nombre de Usuario ya Existe!';
      }else{
         if($pass != $cpass){
            $message[] = 'Confirmar Contraseña no Coincide!';
         }else{
            $insert_admin = $conn->prepare("INSERT INTO `admins`(name, password, image_admin) VALUES(?,?,?)");
            $insert_admin->execute([$name, $cpass, $image_admin]);
            $message[] = 'Nuevo Administrador Registrado con éxito!';
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
   <title>Bijoux - Registrar Administrador</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../../assets/css/css_admin.css">

   <!-- custom icon file link  -->
   <link rel="icon" href="../../assets/images/logo-bijoux1.ico">

</head>
<body>

<?php include '../../../back_end/base_datos/components/admin_header.php'; ?>

<section class="form-container">

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Regístrate Ahora</h3>
      <input type="text" name="name" required placeholder="Ingrese su Nombre de Usuario" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Ingresa tu Contraseña" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Confirmar la Contraseña" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <h2>Foto de Perfil</h2>
		<input type="file" name="image_admin" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
      <input type="submit" value="Regístrate Ahora" class="btn" name="submit">
   </form>

</section>

<script src="../../assets/js/admin_script.js"></script>
   
</body>
</html>