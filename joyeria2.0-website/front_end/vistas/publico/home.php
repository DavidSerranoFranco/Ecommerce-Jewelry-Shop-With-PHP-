<?php

include '../../../back_end/base_datos/components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include '../../../back_end/base_datos/components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bijoux</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../../assets/css/home.css">

   <!-- custom icon file link  -->
   <link rel="icon" href="../../assets/images/logo-bijoux1.ico">

</head>
<body>
   
<?php include '../../../back_end/base_datos/components/user_header.php'; ?>

<div class="home-bg">

<section class="home">

   <div class="swiper home-slider">
   
   <div class="swiper-wrapper">

      <div class="swiper-slide slide">
         <div class="image">
            <img src="../../assets/project images/pendientes/pendiente.png" alt="">
         </div>
         <div class="content">
            <span style="color: black;">Oro 24K</span>
            <h3 style="color: black;">Nuevos Productos de Oro Puro</h3>
            <a href="shop.php" class="btn">Comprar ahora</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="../../assets/project images/anillos/anillo.png" alt="">
         </div>
         <div class="content">
            <span style="color: black;">Oro 14K</span>
            <h3 style="color: black;">La Mejor Joyeria de Oro Puro</h3>
            <a href="shop.php" class="btn">Comprar ahora</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="../../assets/project images/pulseras/pulsera.png" alt="">
         </div>
         <div class="content">
            <span style="color: black;">Oro 24K</span>
            <h3 style="color: black;">Productos Exclusivos en Bijoux</h3>
            <a href="shop.php" class="btn">Comprar ahora</a>
         </div>
      </div>

   </div>

      <div class="swiper-pagination"></div>

   </div>

</section>

</div>

<section class="category">

   <h1 class="heading">Nuestra Categoria</h1>

   <div class="swiper category-slider">

   <div class="swiper-wrapper">

   <a href="category.php?category=pendiente" class="swiper-slide slide">
      <img src="../../assets/images/pendiente-icon.png" alt="">
      <h3>Pendientes</h3>
   </a>

   <a href="category.php?category=collar" class="swiper-slide slide">
      <img src="../../assets/images/collar-icon.png" alt="">
      <h3>Collares</h3>
   </a>

   <a href="category.php?category=pulsera" class="swiper-slide slide">
      <img src="../../assets/images/pulsera-icon.png" alt="">
      <h3>Pulseras</h3>
   </a>

   <a href="category.php?category=anillo" class="swiper-slide slide">
      <img src="../../assets/images/anillo-icon.png" alt="">
      <h3>Anillos</h3>
   </a>

   <a href="category.php?category=broche" class="swiper-slide slide">
      <img src="../../assets/images/broches-icon.png" alt="">
      <h3>Broches</h3>
   </a>

   <a href="category.php?category=reloj" class="swiper-slide slide">
      <img src="../../assets/images/reloj-icon.png" alt="">
      <h3>Relojes</h3>
   </a>

   <a href="category.php?category=piercing" class="swiper-slide slide">
      <img src="../../assets/images/piercing-icon.png" alt="">
      <h3>Piercings</h3>
   </a>

   <a href="category.php?category=tobillera" class="swiper-slide slide">
      <img src="../../assets/images/tobillera-icon.png" alt="">
      <h3>Tobilleras</h3>
   </a>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<section class="home-products">

   <h1 class="heading">Últimos productos</h1>

   <div class="swiper products-slider">

   <div class="swiper-wrapper">

   <?php
     $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="swiper-slide slide">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
      <img src="../../assets/uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_product['name']; ?></div>
      <div class="flex">
         <div class="price"><span>$</span><?= $fetch_product['price']; ?><span>/-</span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="Añadir al Carro" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">No Hay Productos Añadidos Todavía!</p>';
   }
   ?>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>









<?php include '../../../back_end/base_datos/components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="../../assets/js/script.js"></script>

<script>

var swiper = new Swiper(".home-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
    },
});

 var swiper = new Swiper(".category-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
         slidesPerView: 2,
       },
      650: {
        slidesPerView: 3,
      },
      768: {
        slidesPerView: 4,
      },
      1024: {
        slidesPerView: 5,
      },
   },
});

var swiper = new Swiper(".products-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      550: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>