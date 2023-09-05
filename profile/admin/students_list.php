<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hallgatók</title>

  <?php
  include('admin_session_check.php'); // Itt hívjuk meg a session ellenőrzés fájlt
  ?>
</head>

<body>

  <div class="middle">
    <h1 class="font_bold" style="font-size:1.75rem;margin-top:15px;">Hallgatók</h1>
    <h2 class="font_medium" style="font-size:0.85rem;color:rgba(0,0,0,0.5);">Itt tudod kezelni a hallgatókat</h2>
    <br>
    <div id="student-info"></div>
    <div id="student-list"></div>



    <script src="https://code.jquery.com/jquery.js"></script>
    <script src="../../js/refresh.js"></script>
    <script>
      RealTimeRefresh('/profile/admin/students_load.php', 'student-list');
    </script>

  </div>

</body>

</html>