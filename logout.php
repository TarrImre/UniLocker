<?php
// 1. Find the session
session_start();
session_destroy();
//header("Location: login.php");
header("Refresh:0; url=index.html");
?>
