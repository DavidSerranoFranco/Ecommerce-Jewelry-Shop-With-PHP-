<?php

include '../../../back_end/base_datos/components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $_SESSION['user_id'] = $row['id'];
      header('location:home.php');
   }else{
      $message[] = 'Nombre de Usuario o Contraseña Incorrecta!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bijoux - Iniciar Sesion</title>
   
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

   <form action="" method="post">
      <h3>Iniciar Sesion</h3>
      <input type="email" name="email" required placeholder="Ingresar Email" maxlength="50"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Ingresar Contraseña" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Iniciar Sesion" class="btn" name="submit">
      <p>¿No tienes una cuenta?</p>
      <a href="user_register.php" class="option-btn">Registrar ahora</a>
   </form>

</section>

<?php include '../../../back_end/base_datos/components/footer.php'; ?>

<script src="../../assets/js/script.js"></script>

</body>
</html>