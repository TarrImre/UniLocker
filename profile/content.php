<?php
if (isset($_SESSION['neptuncode'])) {
  include('../connection.php');
?>

<?php include('nav.php'); ?>

  <div class="middle">
    <?php
     
    $neptuncode = $_SESSION['neptuncode'];
    $sql = "SELECT id, NeptunCode FROM led WHERE NeptunCode = '$neptuncode'";
    $result = mysqli_query($conn, $sql);

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
<?php
} else echo '<button class="button"><a href="../index.html">Lépj be!</a></button>';
?>