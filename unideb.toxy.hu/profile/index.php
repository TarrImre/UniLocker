<?php
  include('session_check.php'); // Itt hívjuk meg a session ellenőrzés fájlt
  include('../connection.php');
  include('nav.php');
?>

<html>

<head>
  <?php include('header.php'); ?>
</head>

<body>
  <div class="middle">
    <?php
    $neptuncode = $_SESSION['neptuncode'];
    $sql = "SELECT id, NeptunCode FROM lockers WHERE NeptunCode = '$neptuncode'";
    $result = $conn->query($sql);
    if ($result) {

      if (mysqli_num_rows($result) > 0) {
        //echo "true";
        header("Location: mylocker/index.php");
        exit;
      } else {
        //echo "false";
        header("Location: locker/index.php");
        exit;
      }
    } else {
      echo "result false";
    }
    ?>
  </div>
</body>

</html>