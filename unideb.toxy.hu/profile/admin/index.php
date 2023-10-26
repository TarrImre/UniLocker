<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin oldal</title>

  <?php
    include('admin_session_check.php'); // Itt hívjuk meg a session ellenőrzés fájlt
  ?>
</head>

<body>
  <div class="middle" style="background:transparent;box-shadow:none">
    <h1 class="font_bold" style="font-size:1.75rem;">Admin felület</h1>
    <h2 class="font_medium" style="font-size:0.85rem;color:rgba(0,0,0,0.5);">Itt kezelhetsz bármit.</h2>


    <form class="bg" method="POST">
 
      <div class="stat-box">
        <div class="stat-content">
          <div class="stat-value">Felhasználók</div>
          <div class="stat-label">Felhasználók kezelése.</div>
          <button type="submit" name="students_list" class="transparent stat-button">Tovább</button>
        </div>
        <div class="stat-icon">
          <i class='bx bxs-user'></i>
        </div>
      </div>

      <div class="stat-box">
        <div class="stat-content">
          <div class="stat-value">Szekrények</div>
          <div class="stat-label">Szekrények kezelése.</div>
          <button type="submit" name="lockernumberPage" class="transparent stat-button">Tovább</button>
        </div>
        <div class="stat-icon">
          <i class='bx bxs-cabinet' ></i>
        </div>
      </div>

      <div class="stat-box">
        <div class="stat-content">
          <div class="stat-value">Statisztika</div>
          <div class="stat-label">Átfogó statisztikák.</div>
          <button type="submit" name="stat" class="transparent stat-button">Tovább</button>
        </div>
        <div class="stat-icon">
          <i class='bx bxs-objects-vertical-bottom'></i>
        </div>
      </div>

      <div class="stat-box">
        <div class="stat-content">
          <div class="stat-value">API</div>
          <div class="stat-label">Api kezelés.</div>
          <button type="submit" name="api" class="transparent stat-button">Tovább</button>
        </div>
        <div class="stat-icon">
        <i class='bx bxs-cog' ></i>
        </div>
      </div>
      <!--
        <div class="stat-box">
        <div class="stat-content">
          <div class="stat-title">Statisztika</div>
          <div class="stat-value">$3,000</div>
          <div class="stat-label">Augusztus 2023</div>
          <button type="submit" name="stat" class="transparent">Tovább</button>
        </div>
        <div class="stat-icon">
          <i class='bx bxs-objects-vertical-bottom'></i>
        </div>
      </div>
      -->
    </form>
  </div>
</body>

</html>