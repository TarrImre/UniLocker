<?php
session_start();
if (!isset($_SESSION['neptuncode'])) {
    header("Location: needtologin.php");
    exit;
}
?>
