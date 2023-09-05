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

include('../../connection.php');
include('../header.php');
include('../nav.php');
?>