<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Szekrényem</title>

  <?php
  session_start();
  if (isset($_SESSION['neptuncode'])) {
    include('../../connection.php');
    include('../header.php');

  ?>
    <link rel="stylesheet" href="../../scss/css/svglock.css">
</head>

<body>
  <?php include('../nav.php'); ?>
  <div class="middle">
    <div class="screen-1">
      <h1 style="font-family: Helvetica; color: black">A szekrényed</h1>
      <div class="center">
        <div class="form">


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
                            var url = "https://api.toxy.hu/update.php?id=' . $row["id"] . '&status=on&NeptunCode=' . $row["NeptunCode"] . '&UniPassCode=' . $row["UniPassCode"] . '";
                                $.getJSON(url, function(data) {
                                  console.log(data);
                                });
                                document.getElementById("lockerMSG").innerHTML = "A szekrényed nyitva!";  
                              setTimeout(() => {
                                
                                closed = true;
                                  domLock.classList.add("closed");
                                  domLock.style.animation = "none";
                                  animationInProgress = false;
                                
                              }, 3000); // 3 másodperc után visszazárás
                              setTimeout(() => {
                                var url = "https://api.toxy.hu/update.php?id=' . $row["id"] . '&status=off&NeptunCode=' . $row["NeptunCode"] . '&UniPassCode=' . $row["UniPassCode"] . '";
                                $.getJSON(url, function(data) {
                                  console.log(data);
                                });  
                                document.getElementById("lockerMSG").innerHTML = "Ne felejtsd el bezárni!";
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
          
          <div id="lockerMSG"></div>
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