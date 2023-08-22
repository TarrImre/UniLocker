<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Statisztika</title>

  <?php
  session_start();
  if (isset($_SESSION['neptuncode'])) {
    include('../../connection.php');
    include('../header.php');

  ?>
</head>

<body>

  <!--FELHASZNÁLÓK KEZELÉSE-->
  <?php
    include('../nav.php');
    include('stat_load.php');
  ?>

  <div class="middle"  style="background:transparent;box-shadow:none;">
    <h1 class="font_bold" style="font-size:1.75rem;margin-top:15px;">STAT</h1>
    <h2 class="font_medium" style="font-size:0.85rem;color:rgba(0,0,0,0.5);">Itt tudod kezelni a hallgatókat</h2>
    <div class="bg">
      <div class="stat-box" style="margin: 10px;">
        <div class="stat-content" style="margin-right:0px">
          <div class="stat-title" style="color: #0056b3;font-size:20px;margin-bottom:0px;">Foglalt szekrények</div>
          <div class="stat-value" style="color: #99c2ff;font-size:2.75rem;"><?php takenLocker(); ?></div>
          <div class="stat-label">A jelenleg lefoglalt szekrények száma.</div>
        </div>
      </div>
      <div class="stat-box" style="margin: 10px;">
        <div class="stat-content">
          <div class="stat-title" style="color: #8f0000;font-size:20px;margin-bottom:0px;">Felhasználó Regisztrált</div>
          <div class="stat-value" style="color: #ff9999;font-size:2.75rem;"><?php registeredUser(); ?></div>
          <div class="stat-label">Az összes regisztrált felhasználó.</div>
        </div>
      </div>
      <div class="stat-box" style="margin: 10px;">
        <div class="stat-content" style="margin-right:0px">
          <div class="stat-title" style="color: #E2B13C;font-size:20px;margin-bottom:0px;">Regisztrált UniPass kártya</div>
          <div class="stat-value" style="color: #f0d884;font-size:2.75rem;"><?php registeredWithUniPass(); ?></div>
          <div class="stat-label">Felhasználók, akik UniPass kártyával regisztráltak.</div>
        </div>
      </div>
      <div class="stat">
        <div class="statbg">
          <h1 class="font_bold" style="font-size: 2.75rem;"><?php takenLocker(); ?></h1>
          <h2 class="font_medium" style="font-size: 0.85rem;">Szekrény használt</h2>
        </div>

        <div class="statbg">
          <h1 class="font_bold" style="font-size: 2.75rem;"><?php registeredUser(); ?></h1>
          <h2 class="font_medium" style="font-size: 0.85rem;">Felhasználó Regisztrált</h2>
        </div>

        <div class="statbg">
          <h1 class="font_bold" style="font-size: 2.75rem;"><?php registeredWithUniPass(); ?></h1>
          <h2 class="font_medium" style="font-size: 0.85rem;">UniPass Regisztrált</h2>
        </div>

        <div class="statwide">
          <h1 class="font_bold" style="font-size: 1.75rem;color: white">asd</h1>
          <h2 class="font_medium" style="font-size: 0.85rem; color: rgba(0, 0, 0, 0.5); color: white">asd</h2>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery.js"></script>
  <script src="../../js/refresh.js"></script>
  <script>
    RealTimeRefresh('/profile/admin/stat_load.php', 'number-list');
  </script>

<?php
  } else {
    echo '<button class="button"><a href="../../index.html">Lépj be!</a></button>';
    exit;
  }
?>
</body>

</html>