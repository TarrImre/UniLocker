<?php
// 1. Find the session
session_start();
unset($_SESSION['id']);
unset($_SESSION['neptuncode']);
unset($_SESSION['UniPassCode']);
//session_destroy();
//header("Location: login.php");
header("Refresh:0; url=index.html");
?>
