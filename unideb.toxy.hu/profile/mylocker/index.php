<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Szekrényem</title>

  <?php
  include('../session_check.php');
  include('../../connection.php');
  include('../header.php');
  include('../nav.php');
  ?>
  <link rel="stylesheet" href="../../scss/css/svglock.css">
  <style>
    .circle{
      margin-top:60px;
    }
  </style>
</head>

<body>

  <div class="middle" style="background-color:transparent;box-shadow:none;">
    <!--h1>A szekrényed</h1-->
    <?php
    //VARIABLES
    $neptunCode = $_SESSION['neptuncode'];

    $UniPassCode = $_SESSION['UniPassCode'];
    $empty = "";
    $msg = "";
    $availableLocker = true;
    include('../apikeyfunction.php');
    ?>

    <?php
    //Select my neptuncode from the database and write out with id

    $sql = "SELECT id, NeptunCode, UniPassCode FROM lockers WHERE NeptunCode = '$neptunCode'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // output data of each row
      while ($row = $result->fetch_assoc()) {


        echo '<div class="circle">';
        echo '<svg id="toggleButton" class="lock closed" viewBox="0 0 184 220.19">
                        <clipPath id="clip-path">
                          <rect class="fill-mask" x="7.5" y="97.69" width="169" height="115" rx="18.5" ry="18.5"/>
                        </clipPath>
                        <g class="fill-mask-group">
                          <circle class="fill-circle" cx="142.5" cy="97.69" r="180"/>
                        </g>

                        <path class="top-part" d="M41.5,93.69V56.93A49.24,49.24,0,0,1,90.73,7.69h2.54A49.24,49.24,0,0,1,142.5,56.93v33"/>
                        <rect class="bottom-part" x="7.5" y="97.69" width="169" height="115" rx="18.5" ry="18.5"/>
                    </svg>';
        echo "<div class='mylocker-number'>" . $row["id"] . ".</div>";
        echo '</div>';


              echo '<script>
              const domLock = document.querySelector(".lock");
              let closed = true; // Az alapértelmezett állapot most zárt
        
              function closeLock() {
                closed = true;
                domLock.classList.add("closed");
                domLock.querySelector(".fill-circle").setAttribute("r", 180);
                domLock.querySelector(".top-part").setAttribute("d", "M41.5,93.69V56.93A49.24,49.24,0,0,1,90.73,7.69h2.54A49.24,49.24,0,0,1,142.5,56.93v33");
                var url = "https://api.toxy.hu/update.php?id=' . $row["id"] . '&status=off&NeptunCode=' . $row["NeptunCode"] . '&UniPassCode=' . $row["UniPassCode"] . '&apikey=' . getApiKey() . '";
                $.getJSON(url, function(data) {
                console.log(data);
                });  
                document.getElementById("lockerMSG").innerHTML = "Ne felejtsd el <b>bezárni!</b>";
              }
        
              domLock.addEventListener("click", () => {
                if (closed) {
                  closed = false;
                  domLock.classList.remove("closed");
                  domLock.querySelector(".fill-circle").setAttribute("r", 1.5);
                  domLock.querySelector(".top-part").setAttribute("d", "M41.5,93.69V56.93A49.24,49.24,0,0,1,90.73,7.69h2.54A49.24,49.24,0,0,1,142.5,56.93v2.26");
                  var url = "https://api.toxy.hu/update.php?id=' . $row["id"] . '&status=on&NeptunCode=' . $row["NeptunCode"] . '&UniPassCode=' . $row["UniPassCode"] . '&apikey=' . getApiKey() . '";
                  $.getJSON(url, function(data) {
                  console.log(data);
                  });
                  document.getElementById("lockerMSG").innerHTML = "A szekrényed <b>nyitva!</b>";  
                  setTimeout(closeLock, 600); // 0.6 másodperc után automatikusan bezár
                }
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
      <div style="display: flex;justify-content: center;align-items: center;">
        <div id="lockerMSG" style="margin-top:60px;position:absolute;font-size:1.15rem;"></div>
      </div>
      <button type="submit" name="lead" id="deleteButton" class="button" style="margin-top:100px;">Szekrény leadás</button>
      <br />
    </form>
    <?php

    if (isset($_POST['lead'])) {
      $sql = "SELECT id, NeptunCode FROM lockers WHERE NeptunCode = '$neptunCode'";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
          // echo "<br> Szekrényed: " . $row["id"] . " - Neptun kód: " . $row["NeptunCode"] . "<br><br>";
          $json = file_get_contents('http://api.toxy.hu/update.php?id=' . $row["id"] . '&status=off&NeptunCode=' . $empty . '&UniPassCode=' . $empty . '&apikey=' . getApiKey() . '');
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

</body>

</html>