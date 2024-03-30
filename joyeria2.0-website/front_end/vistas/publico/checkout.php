<?php

include '../../../back_end/base_datos/components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};

if(isset($_POST['order'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = 'flat no. '. $_POST['flat'] .', '. $_POST['street'] .', '. $_POST['city'] .', '. $_POST['state'] .', '. $_POST['country'] .' - '. $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){

      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      $message[] = 'Pedido Realizado Correctamente!';
   }else{
      $message[] = 'Tu Carrito Esta Vacío';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bijoux - Pago</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../../assets/css/home.css">

   <!-- custom icon file link  -->
   <link rel="icon" href="../../assets/images/logo-bijoux1.ico">

</head>
<body>
   
<?php include '../../../back_end/base_datos/components/user_header.php'; ?>

<section class="checkout-orders">

   <form action="" method="POST">

   <h3>Tus Productos</h3>

      <div class="display-orders">
      <?php
         $grand_total = 0;
         $cart_items[] = '';
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
               $total_products = implode($cart_items);
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
      ?>
         <p> <?= $fetch_cart['name']; ?> <span>(<?= '$'.$fetch_cart['price'].'/- x '. $fetch_cart['quantity']; ?>)</span> </p>
      <?php
            }
         }else{
            echo '<p class="empty">Tu Carrito Esta Vacío!</p>';
         }
      ?>
         <input type="hidden" name="total_products" value="<?= $total_products; ?>">
         <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
         <div class="grand-total">total : <span>$<?= $grand_total; ?>/-</span></div>
      </div>

      <h3>hagan sus pedidos</h3>

      <div class="flex">
         <div class="inputBox">
            <span>tu nombre :</span>
            <input type="text" name="name" placeholder="introduzca su nombre" class="box" maxlength="20" required>
         </div>
         <div class="inputBox">
            <span>tu numero :</span>
            <input type="number" name="number" placeholder="introduzca tu numero" class="box" min="0" max="9999999999" onkeypress="if(this.value.length == 10) return false;" required>
         </div>
         <div class="inputBox">
            <span>tu email :</span>
            <input type="email" name="email" placeholder="introduzca su email" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>método de pago :</span>
            <select name="method" class="box" required>
               <!--<option value="cash on delivery">contra reembolso</option>-->
               <option value="credit card">tarjeta de crédito / debito</option>
               <!--<option value="paytm">pago</option>-->
               <option value="paypal">paypal</option>
            </select>
         </div>
         <div class="inputBox">
            <span>línea de dirección 01 :</span>
            <input type="text" name="flat" placeholder="p.ej. número plano" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>línea de dirección 02 :</span>
            <input type="text" name="street" placeholder="p.ej. nombre de la calle" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>ciudad :</span>
            <input type="text" name="city" placeholder="p.ej. Monterrey" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>estado :</span>
            <input type="text" name="state" placeholder="p.ej. Nuevo Leon" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>país :</span>
            <input type="text" name="country" placeholder="p.ej. Mexico" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>código PIN :</span>
            <input type="number" min="0" name="pin_code" placeholder="p.ej. 123456" min="0" max="999999" onkeypress="if(this.value.length == 6) return false;" class="box" required>
         </div>
      </div>

      <input type="submit" name="order" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>" value="realizar pedido">

   </form>

</section>













<?php include '../../../back_end/base_datos/components/footer.php'; ?>

<script src="../../assets/js/script.js"></script>

</body>
</html>