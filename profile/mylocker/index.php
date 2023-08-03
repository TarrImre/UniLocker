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
<?php include('../nav.php'); ?>
<div class="middle">
  <div class="screen-1">
    <center>
      <h1 style="font-family: Helvetica; color: black">A szekrényed</h1>
    </center>
    <div class="center">
      <div align="center" class="form">


        <?php
        //VARIABLES
        $neptunCode = $_SESSION['neptuncode'];

        $UniPassCode = $_SESSION['UniPassCode'];
        $empty = "";
        $msg = "";
        $availableLocker = true;
        ?>

        <?php
        //Select my neptuncode from the database and write out with id


        $sql = "SELECT id, NeptunCode, UniPassCode FROM led WHERE NeptunCode = '$neptunCode'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          // output data of each row
          while ($row = $result->fetch_assoc()) {
            echo "<br> Szekrényed: " . $row["id"] . " - Neptun kód: " . $row["NeptunCode"] . "<br><br>";
            echo '<button type="button" id="D2-on" class="button button1">Nyit</button>
                  <button type="button" id="D2-off" class="button button1">Zár...</button>';

            echo '<script type="text/javascript">
                  document.getElementById("D2-on").style.display = "inline";
                  document.getElementById("D2-off").style.display = "none";
              
                  document.getElementById("D2-on").addEventListener("click", function() {
                    var url = "https://api.toxy.hu/update.php?id=' . $row["id"] . '&status=on&NeptunCode=' . $row["NeptunCode"] . '&UniPassCode=' . $row["UniPassCode"] . '";
                    document.getElementById("D2-on").style.display = "none";
                    document.getElementById("D2-off").style.display = "inline";
                    $.getJSON(url, function(data) {
                      console.log(data);
                    });
                    setTimeout(() => {
                      var url = "https://api.toxy.hu/update.php?id=' . $row["id"] . '&status=off&NeptunCode=' . $row["NeptunCode"] . '&UniPassCode=' . $row["UniPassCode"] . '";
                      document.getElementById("D2-on").style.display = "inline";
                      document.getElementById("D2-off").style.display = "none";
                      $.getJSON(url, function(data) {
                        console.log(data);
                      });
                    }, 2000);
                  });
        
                  </script>';
          }
        } else {
        //  echo  "Nincs foglalt szekrényed" . "<br><br>";
          echo '<script>
            (function(){
              window.location.href = "../index.php";
          })();
          </script>';
        }
        ?>


        <form action="" method="POST">

          <button type="submit" name="lead" id="deleteButton">Szekrény leadás</button>


          <br />
        </form>
        <?php

        if (isset($_POST['lead'])) {
          $sql = "SELECT id, NeptunCode FROM led WHERE NeptunCode = '$neptunCode'";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
              // echo "<br> Szekrényed: " . $row["id"] . " - Neptun kód: " . $row["NeptunCode"] . "<br><br>";
              $json = file_get_contents('http://api.toxy.hu/update.php?id=' . $row["id"] . '&status=off&NeptunCode=' . $empty . '&UniPassCode=' . $empty . '');
              $obj = json_decode($json);
              successMsg("Sikeresen leadtad!", "A szekrényed leadása sikeres volt.");
              //header("Refresh:2;");
             
              echo '<script>
                window.setTimeout(function(){
                    window.location.href = "../index.php";
                }, 2000);
                </script>';



              exit;
            }
          }
        }
        ?>

        <br>

      </div>
    </div>

  </div>
  </div>

<?php
} else echo '<button class="button"><a href="../../index.php">Lépj be!</a></button>';
?>
</body>
</html>