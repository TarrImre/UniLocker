<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
 
<?php
session_start();
if (isset($_SESSION['neptuncode'])) {
  include('../../connection.php');
  include('../header.php');

?>
</head>
<body>
<?php include('../nav.php');?>
<div class="middle">
  <h1 class="font_bold" style="font-size:1.75rem;margin-top:15px;">Admin page</h1>
  <h2 class="font_medium" style="font-size:0.85rem;color:rgba(0,0,0,0.5);">sdf</h2>


  <form class="bg" method="POST">
    <button type="submit" name="options" class="transparent yellow-gradient-square">
      <div class="text-content">
        <h1 class="font_bold" style="font-size: 1.75rem;color: white">Felhasználók</h1>
        <h2 class="font_medium" style="font-size: 0.85rem; color: rgba(0, 0, 0, 0.5); color: white">Felhasználók kezelése</h2>
      </div>
    </button>

    <button type="submit" name="lockernumberPage" class="transparent blue-gradient-square">
      <div class="text-content">
        <h1 class="font_bold" style="font-size: 1.75rem;color: white">Szekrények</h1>
        <h2 class="font_medium" style="font-size: 0.85rem; color: rgba(0, 0, 0, 0.5); color: white">Szekrények kezelése</h2>
      </div>
    </button>

    <button type="submit" name="options" class="transparent green-gradient-square">
      <div class="text-content">
        <h1 class="font_bold" style="font-size: 1.75rem;color: white">Nem tudom</h1>
        <h2 class="font_medium" style="font-size: 0.85rem; color: rgba(0, 0, 0, 0.5); color: white">Nem tudom</h2>
      </div>
    </button>
  </form>
  </div>
  
<?php
} else {
  echo '<button class="button"><a href="../../index.html">Lépj be!</a></button>';
}
?>
</body>
</html>