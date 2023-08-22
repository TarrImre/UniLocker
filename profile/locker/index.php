<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Szekrények</title>
  <script src="../../js/locker.js"></script>
  <?php
  session_start();
  if (isset($_SESSION['neptuncode'])) {
    include('../../connection.php');
    include('../header.php');

  ?>

</head>

<body>

  <script>
    function deleteStoredCheckboxStates() {
      localStorage.removeItem('CBState');
      cbstate = {};
    }
    deleteStoredCheckboxStates();
  </script>


  <?php include('../nav.php'); ?>
  <div class="middle">
    <h1 class="font_bold" style="font-size:1.75rem">Available lockers</h1>
    <h2 class="font_medium" style="font-size:0.85rem;color:rgba(0,0,0,0.5);">Select one and press the tick</h2>
    <br>

    <div style="text-align:center;">
      <?php
      //VARIABLES
      $neptunCode = $_SESSION['neptuncode'];
      $UniPassCode = $_SESSION['UniPassCode'];

      $empty = "";
      $availableLocker = true;
      ?>

      
      <form action="" method="POST" name="main">
        <?php
        $lockersNumber = 0;
        //query the lockers number from the database
        $sql = "SELECT number FROM lockernumber WHERE id=1";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {

            $lockersNumber = $row["number"];
          }
        }

        for ($i = 1; $i <= $lockersNumber; $i++) {


          echo '<div id="dynamicDiv"></div>';


          //cant choose the locker if it is already taken
          $sql = "SELECT id, NeptunCode FROM led WHERE id = '$i'";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              if ($row["NeptunCode"] != "") {
                $availableLocker = false;
              } else {
                $availableLocker = true;
              }
            }
          }




          if (isset($_POST[$i]) && $availableLocker && isset($_POST['kuld'])) {

            $json = file_get_contents('http://api.toxy.hu/update.php?id=' . $_POST[$i] . '&status=off&NeptunCode=' . $neptunCode . '&UniPassCode=' . $UniPassCode . '');
            $obj = json_decode($json);
            successMsg("Sikeres foglalás!", "A szekrényed elérhető.");

            //header("Refresh:2;");
            echo '<script>
          window.setTimeout(function(){
              window.location.href = "../mylocker/index.php";
          }, 2000);
          </script>';
            exit;
          }
        }

        $available = "SELECT NeptunCode FROM led WHERE NeptunCode='$neptunCode'";
        $availableResult = mysqli_query($conn, $available);
        $available = mysqli_num_rows($availableResult);
        if ($available >= 1) {
          $availableLocker = false;
          //echo "Már van szekrényed vagy foglalt!";
          echo '<script>
            (function(){
              window.location.href = "../index.php";
          })();
          </script>';
        }

        ?>

        <!-- <button id="loading-button" class="test" type="submit" name="kuld" onclick="countCheckboxes()">Kérem</button>-->


        <div class="overlay">
          <button id="loading-button" class="overlay-button" type="submit" name="kuld" onclick="countCheckboxes()"><i class='bx bx-check'></i></button>
        </div>

        <br />
      </form>

      <br>

    </div>
  </div>

  <script src="https://code.jquery.com/jquery.js"></script>
  <!-- This JavaScript file is required to load the XpressDox interview as well as the code required to run it -->
  <script src="../../js/refresh.js"></script>
  <script>
    RealTimeRefreshCheckbox('https://unideb.toxy.hu/profile/locker/lockers.php', 'dynamicDiv');
  </script>

<?php
  } else echo '<button class="button"><a href="../../index.html">Lépj be!</a></button>';
?>
</body>

</html>