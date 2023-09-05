<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Szekrényem</title>

  <?php
    include('../session_check.php'); // Itt hívjuk meg a session ellenőrzés fájlt
    include('../../connection.php');
    include('../header.php');

  ?>
    <link rel="stylesheet" href="../../scss/css/svglock.css">
</head>

<body>
  <?php include('../nav.php'); ?>
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

        echo "<h1 style='margin-bottom:30px;' class='font_bold'>Szekrényszám: " . $row["id"] . "</h1>";
        /*  echo '<button type="button" id="D2-on" class="button button1">Nyit</button>
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
        
                  </script>';*/

        echo '<div class="circle">';
        echo '<svg id="toggleButton" class="lock closed" viewBox="0 0 184 220.19">
                        <clipPath id="clip-path">
                          <rect class="fill-mask" x="7.5" y="97.69" width="169" height="115" rx="18.5" ry="18.5"/>
                        </clipPath>
                        <g class="fill-mask-group">
                          <circle class="fill-circle" cx="142.5" cy="97.69" r="1.5"/>
                        </g>
                        <path class="top-part" d="M41.5,93.69V56.93A49.24,49.24,0,0,1,90.73,7.69h2.54A49.24,49.24,0,0,1,142.5,56.93v2.26"/>
                        <rect class="bottom-part" x="7.5" y="97.69" width="169" height="115" rx="18.5" ry="18.5"/>
                    </svg>';
        echo '</div>';


        echo '<script>
              const domLock = document.querySelector(".lock");
              const toggleButton = document.getElementById("toggleButton");
              let closed = true;
              let animationInProgress = false;
              
              toggleButton.addEventListener("click", () => {
                  if (!animationInProgress) {
                    
                      closed = !closed;
                      animationInProgress = true;
                      domLock.classList.toggle("closed", closed);
              
                      let anim = closed ?
                          "LinearShake ease-in-out 280ms, 360ms AngularShake ease-in-out 280ms" :
                          "LinearShake ease-in-out 280ms";
              
                      domLock.style.animation = "none";
                      setTimeout(() => {
                          domLock.style.animation = anim;
                          if (!closed) {
                            var url = "https://api.toxy.hu/update.php?id=' . $row["id"] . '&status=on&NeptunCode=' . $row["NeptunCode"] . '&UniPassCode=' . $row["UniPassCode"] . '&apikey=' . getApiKey() . '";
                                $.getJSON(url, function(data) {
                                  console.log(data);
                                });
                                document.getElementById("lockerMSG").innerHTML = "A szekrényed <b>nyitva!</b>";  
                              setTimeout(() => {
                                
                                closed = true;
                                  domLock.classList.add("closed");
                                  domLock.style.animation = "none";
                                  animationInProgress = false;
                                
                              }, 3000); // 3 másodperc után visszazárás
                              setTimeout(() => {
                                var url = "https://api.toxy.hu/update.php?id=' . $row["id"] . '&status=off&NeptunCode=' . $row["NeptunCode"] . '&UniPassCode=' . $row["UniPassCode"] . '&apikey=' . getApiKey() . '";
                                $.getJSON(url, function(data) {
                                  console.log(data);
                                });  
                                document.getElementById("lockerMSG").innerHTML = "Ne felejtsd el <b>bezárni!</b>";
                              }, 3000); // 3 másodperc után visszazárás
                          }
                          else {
                            animationInProgress = false;
                          }
                      }, 0);
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
        <div id="lockerMSG" style="margin-top:100px;position:absolute;font-size:1.15rem;"></div>
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