<?php

include '../../../back_end/base_datos/components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bijoux - Orden</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../../assets/css/home.css">

   <!-- custom icon file link  -->
   <link rel="icon" href="../../assets/images/logo-bijoux1.ico">

</head>
<body>
   
<?php include '../../../back_end/base_datos/components/user_header.php'; ?>

<section class="orders">

   <h1 class="heading">Pedido Realizado</h1>

   <div class="box-container">

   <?php
      if($user_id == ''){
         echo '<p class="empty">Por Favor Inicie Sesión Para Ver Sus Pedidos</p>';
      }else{
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
         $select_orders->execute([$user_id]);
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p>Fecha de Pedido : <span><?= $fetch_orders['placed_on']; ?></span></p>
      <p>Nombre : <span><?= $fetch_orders['name']; ?></span></p>
      <p>Email : <span><?= $fetch_orders['email']; ?></span></p>
      <p>Numero : <span><?= $fetch_orders['number']; ?></span></p>
      <p>Direccion : <span><?= $fetch_orders['address']; ?></span></p>
      <p>Método de Pago : <span><?= $fetch_orders['method']; ?></span></p>
      <p>Tus Productos : <span><?= $fetch_orders['total_products']; ?></span></p>
      <p>Precio Total : <span>$<?= $fetch_orders['total_price']; ?>/-</span></p>
      <p> Estado de Pago : <span style="color:<?php if($fetch_orders['payment_status'] == 'pendiente'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['payment_status']; ?></span> </p>
   </div>
   <?php
      }
      }else{
         echo '<p class="empty">No Hay Pedidos Realizados Todavía!</p>';
      }
      }
   ?>

   </div>

</section>













<?php include '../../../back_end/base_datos/components/footer.php'; ?>

<script src="../../assets/js/script.js"></script>

</body>
</html>