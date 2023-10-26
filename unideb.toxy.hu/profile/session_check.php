<?php
session_start();

if (!isset($_SESSION['neptuncode'])) {
    //dinamikus útvonal
    //a többi include is kell 
    header("Location: https://unideb.toxy.hu/profile/needtologin.php");
    exit;
}


?>

