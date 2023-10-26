<?php
session_start();

// Ellenőrizzük, hogy be van-e jelentkezve a felhasználó
if (!isset($_SESSION['neptuncode'])) {
  header("Location: ../needtologin.php");
  exit;
}

// Ellenőrizzük, hogy a felhasználó "Admin" rangú-e
if (isset($_SESSION['Rank']) && $_SESSION['Rank'] !== "Admin") {
  //echo "Nincs jogosultságod az oldal megtekintéséhez!";
  header("Location: ../needtologin.php");
  exit;
}


$rootPath = $_SERVER['DOCUMENT_ROOT'];

$connectionPath = $rootPath . '/connection.php';
$headerPath = $rootPath . '/profile/header.php';
$navPath = $rootPath . '/profile/nav.php';

include($connectionPath);
include($headerPath);
include($navPath);


?>