<?php

include './connect.php';

session_start();
session_unset();
session_destroy();

header('location:../../../front_end/vistas/publico/home.php');

?>