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

  <script>
    function deleteStoredCheckboxStates() {
      localStorage.removeItem('CBState');
      cbstate = {};
    }
    deleteStoredCheckboxStates();
  </script>


  <?php include('../nav.php'); ?>
  <div class="middle">
  <h1 class="font_bold" style="font-size:1.75rem">Available lockers</h1><h2 class="font_medium" style="font-size:0.85rem;color:rgba(0,0,0,0.5);">Select one and press the tick</h2>
  <br>

  <div style="text-align:center;">
    <?php
    //VARIABLES
    $neptunCode = $_SESSION['neptuncode'];
    $UniPassCode = $_SESSION['UniPassCode'];

    $empty = "";
    $availableLocker = true;
    ?>
    <script>
      //count how many checkbox button checked, and alert when selected more than one
      /*  $(document).ready(function() {
          $("input[type='checkbox']").change(function() {
            var maxAllowed = 1;
            var cnt = $("input[type='checkbox']:checked").length;
            if (cnt > maxAllowed) {
              $(this).prop("checked", "");
              Swal.fire({
                icon: 'error',
                title: 'Maximum 1 szekrényt választhatsz!',
                text: 'Próbáld újra!',
                timer: 1500,
                allowOutsideClick: false,
                showCancelButton: false,
                showConfirmButton: false
              });
            }
          });
        });*/
    </script>
    <script>
      function countCheckboxes() {
        var a = document.forms["main"];
        var x = a.querySelectorAll('input[type="checkbox"]:checked');
        console.log(x.length);
        if (x.length > 1) {
          Swal.fire({
            icon: 'error',
            title: 'Maximum 1 szekrényt választhatsz!',
            text: 'Próbáld újra!',
            timer: 1500,
            allowOutsideClick: false,
            showCancelButton: false,
            showConfirmButton: false
          });
          event.preventDefault();

   
        }
      }
    </script>

    <form action="" method="POST" name="main">
      <?php
      $lockersNumber=0;
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



        /*   echo '
                  <section class="buttons-wrapper">
                    <div>
                    
                      <label class="toggler-wrapper style-1">
                        <input type="checkbox" >
                        <div class="toggler-slider">
                          <div class="toggler-knob"></div>
                        </div>
                      </label>
                      <div class="badge">asd</div>
                    </div>
                  </section>';*/
        //if the locker is already taken by someone color it to red the text


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


        /*$available = "SELECT NeptunCode FROM led WHERE NeptunCode='$neptunCode'";
                  $availableResult = mysqli_query($conn, $available);
                  $available = mysqli_num_rows($availableResult);
                  if ($available>=1) {
                    $availableLocker = false;
                    $msg = "Már van szekrényed vagy foglalt!";
                  }*/


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

  <script>
    (function() {
      // Lokális változók
      var cbstate = {};

      // Az oldal betöltésekor
      window.addEventListener('load', function() {
        // A checkbox állapotok visszaállítása
        cbstate = JSON.parse(localStorage.getItem('CBState')) || {};

        // Hallgatók listájának frissítése
        updateStudentList();

        // Periodikus frissítés beállítása
        setInterval(updateStudentList, 2000);
      });

      // Hallgatók listájának frissítése
      function updateStudentList() {
        // AJAX kérés az új hallgatók listájának lekérésére
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'https://unideb.toxy.hu/profile/locker/lockers.php', true);
        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4 && xhr.status === 200) {
            // A hallgatók listájának frissítése
            document.getElementById('dynamicDiv').innerHTML = xhr.responseText;

            // Checkbox állapotok visszaállítása
            restoreCheckboxStates();
          }
        };
        console.log("refresh");
        xhr.send();
      }

      // Checkbox állapotok visszaállítása
      function restoreCheckboxStates() {
        var checkboxes = document.querySelectorAll('.save-cb-state');
        checkboxes.forEach(function(checkbox) {
          checkbox.checked = cbstate[checkbox.name] || false;
          checkbox.addEventListener('change', function(event) {
            cbstate[checkbox.name] = checkbox.checked;
            localStorage.setItem('CBState', JSON.stringify(cbstate));
          });
        });
      }
  
    })();
  </script>

<?php
} else echo '<button class="button"><a href="../../index.html">Lépj be!</a></button>';
?>
</body>
</html>