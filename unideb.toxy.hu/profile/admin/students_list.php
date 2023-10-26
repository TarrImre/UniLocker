<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Keresés</title>
  <?php
  include('admin_session_check.php'); // Itt hívjuk meg a session ellenőrzés fájlt
  include('../../input_validation.php');
  ?>
  <style>
    /* Thick red border */
    hr {
      border: 1px solid #888888;
    }

    .i-icons {
      font-size: 35px;
      margin-left: 15px;
      margin-right: 15px;
      margin-top: 10px;
      margin-bottom: 10px;
      color: #888888;
      transition: color 0.5s ease;
    }

    .i-icons:hover {
      color: #333333;
    }

  </style>
</head>

<body>
  <div class="middle" style="width: 320px;">
    <h1 class="font_bold" style="font-size:1.75rem;margin-top:15px;">Hallgatók</h1>
    <h2 class="font_medium" style="font-size:0.85rem;color:rgba(0,0,0,0.5);">Itt tudod kezelni a hallgatókat</h2>
    <br>
    <!-- Űrlap kereséshez -->
    <form method="POST" style="margin-bottom:10px;">
      <input type="text" class="input_style" style="width:200px;" name="search" placeholder="Neptun kód">
      <button type="submit" name="search_btn" style="width:80px;padding:10px;font-weight:normal;" class="button">Keresés</button>
    </form>
    <div>
      <?php
      if (isset($_POST['search_btn'])) {
        $search = $_POST['search'];
        $query = "SELECT * FROM users WHERE NeptunCode='$search'";
        $result = mysqli_query($conn, $query);

        if ($result) {
          if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
              echo '<script>enable_readonly();</script>';
              //Amikor a felhasználó beküld egy űrlapot, a böngésző alapértelmezés szerint elmenti az oldal állapotát a böngésző előzményeiben (history), így a felhasználó vissza tud térni az űrlap elküldése előtti oldali állapotra. Ezzel a kóddal azonban a window.history.replaceState funkcióval az oldal állapota a beküldés után azonnal megváltozik (null, null, window.location.href), és ezzel megakadályozza az űrlap újbóli elküldését, amikor a felhasználó a vissza gombot vagy a frissítést használja
              echo '<script type="text/javascript">
              if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
              }
            </script>';


              $queryLockerNumber = "SELECT * FROM lockers WHERE NeptunCode='$search'";
              $resultLockerNumber = mysqli_query($conn, $queryLockerNumber);
              $rowLockerNumber = mysqli_fetch_array($resultLockerNumber);
              if ($rowLockerNumber != null) {
                $lockerNumber = $rowLockerNumber['id'];
              } else {
                $lockerNumber = "";
              }
      ?>
              <hr>
              <form method="POST">
                <div class="input_box">
                  <div class="box">
                    <p>Vezetéknév</p>
                    <input type="text" class="input_style enable" name="VName" value="<?php echo $row['VName']; ?>" readonly>
                  </div>
                  <div class="box">
                    <p>Keresztnév</p>
                    <input type="text" class="input_style enable" name="KName" value="<?php echo $row['KName']; ?>" readonly>
                  </div>
                  <div class="box">
                    <p>Email</p>
                    <input type="text" class="input_style enable" name="Email" value="<?php echo $row['Email']; ?>" readonly>
                  </div>
                  <div class="box">
                    <p>Rang</p>
                    <input type="text" class="input_style enable" name="Rank" value="<?php echo $row['Rank']; ?>" readonly>
                  </div>
                  <div class="box">
                    <p>Neptun kód</p>
                    <input type="text" class="input_style" name="NeptunCode" value="<?php echo $row['NeptunCode']; ?>" readonly>
                  </div>
                  <div class="box">
                    <p>UniPass</p>
                    <input type="text" class="input_style" name="UniPassCode" value="<?php echo $row['UniPassCode']; ?>" readonly>
                  </div>
                  <div class="box">
                    <p>Szekrény</p>
                    <input type="text" class="input_style" name="LockerNumber" value="<?php echo isset($lockerNumber) ? $lockerNumber : "" ?>" readonly>
                  </div>
                  <div class="box">
                    <p>Létrehozva</p>
                    <input type="text" class="input_style" name="CreatedAT" value="<?php echo $row['CreatedAT']; ?>" readonly>
                  </div>
                </div>
                <div class="box">
                  <button class="transparent i-icons hide" name="check"><i class='bx bxs-check-circle'></i></button>
                  <button type="button" class="transparent i-icons hide" name="cancel"><i class='bx bxs-x-circle'></i></button>
                  <button class="transparent i-icons hide" name="trash"><i class='bx bx-trash'></i></button>
                </div>
              </form>

              <button class="transparent enable_button i-icons" name="edit"><i class='bx bx-edit'></i></button>


      <?php
            }
          } else {
            echo "<p style='margin-bottom:10px;'>Nincs ilyen hallgató!</p>";
          }
        } else {
          echo "Lekérdezési hiba: " . mysqli_error($conn);
        }
      }


      if (isset($_POST['check'])) {
        $VName = $_POST['VName'];
        $KName = $_POST['KName'];
        $Email = $_POST['Email'];
        $Rank = $_POST['Rank'];
        $NeptunCode = $_POST['NeptunCode'];

        $VNameError = validateVName($VName);
        $KNameError = validateKName($KName);
        $EmailError = validateEmail($Email);

        $emailExistError = validateEmailExist($conn, $Email);

        //check if the email is already in use by another user and if it is not the same user's email 
        $query = "SELECT * FROM users WHERE NeptunCode='$NeptunCode'";
        $result = mysqli_query($conn, $query);
        $rowEmailExist = mysqli_fetch_array($result);


        if ($VNameError) {
          errorMsg("Hibás Vezetéknév!", $VNameError);
        } else if ($KNameError) {
          errorMsg("Hibás Keresztnév!", $KNameError);
        } else if ($EmailError) {
          errorMsg("Hibás Email!", $EmailError);
        } else if ($rowEmailExist['Email'] != $Email) {
          if ($emailExistError) {
            errorMsg("Hibás Email!", $emailExistError);
          }
          else {
            $query = "UPDATE users SET VName='$VName', KName='$KName', Email='$Email', Rank='$Rank' WHERE NeptunCode='$NeptunCode'";
            $query_run = mysqli_query($conn, $query);
            if ($query_run) {
              echo '<p style="margin-bottom:10px;">Sikeres módosítás</p>';
            } else {
              echo '<p style="margin-bottom:10px;">Sikertelen módosítás</p>';
            }
          }
        } else {
          $query = "UPDATE users SET VName='$VName', KName='$KName', Email='$Email', Rank='$Rank' WHERE NeptunCode='$NeptunCode'";
          $query_run = mysqli_query($conn, $query);
          if ($query_run) {
            echo '<p style="margin-bottom:10px;">Sikeres módosítás</p>';
          } else {
            echo '<p style="margin-bottom:10px;">Sikertelen módosítás</p>';
          }
        }
      }

      function Update(){
        global $conn;
        global $VName;
        global $KName;
        global $Email;
        global $Rank;
        global $NeptunCode;
        
        $query = "UPDATE users SET VName='$VName', KName='$KName', Email='$Email', Rank='$Rank' WHERE NeptunCode='$NeptunCode'";
            $query_run = mysqli_query($conn, $query);
            if ($query_run) {
              echo '<p style="margin-bottom:10px;">Sikeres módosítás</p>';
            } else {
              echo '<p style="margin-bottom:10px;">Sikertelen módosítás</p>';
            }
      }

  
      //make the trash button to delete the user, but firstly ask the user to confirm the action with sweetalert
      if (isset($_POST['trash'])) {
        $NeptunCode = $_POST['NeptunCode'];
        questionMsg('A felhasználó végleg törlődik!','Biztos vagy benne?','students_list.php?NeptunCode='.$NeptunCode.'');
      }

      if (isset($_GET['NeptunCode'])) {
        $NeptunCode = $_GET['NeptunCode'];
        $query = "DELETE FROM users WHERE NeptunCode='$NeptunCode'";
        $query_run = mysqli_query($conn, $query);
        if ($query_run) {
          echo '<p style="margin-bottom:10px;">Sikeres törlés</p>';
        } else {
          echo '<p style="margin-bottom:10px;">Sikertelen törlés</p>';
        }
      }



      ?>
    </div>
    <!--script to disable readonly-->
    <script>
      const cancel = document.querySelector('.i-icons[name="cancel"]');
      const check = document.querySelector('.i-icons[name="check"]');
      const trash = document.querySelector('.i-icons[name="trash"]');

      cancel.addEventListener('click', function() {
        //event.preventDefault(); // Megakadályozza a form elküldését
        enable_readonly();
        cancel.classList.add('hide');
        document.querySelector('.i-icons[name="check"]').classList.add('hide');
        document.querySelector('.i-icons[name="trash"]').classList.add('hide');
        document.querySelector('.enable_button').classList.remove('hide');

      });


      const button = document.querySelector('.enable_button');
      button.addEventListener('click', function() {
        disable_readonly();
        button.classList.add('hide');
        cancel.classList.remove('hide');
        check.classList.remove('hide');
        trash.classList.remove('hide');
      });

      function disable_readonly() {
        const inputs = document.getElementsByClassName("enable");
        for (var i = 0; i < inputs.length; i++) {
          inputs[i].readOnly = false;
          inputs[i].style.color = "#1c1c1c";
          inputs[i].style.fontWeight = "bold";
        }
      }

      function enable_readonly() {
        const inputs = document.getElementsByClassName("enable");
        for (var i = 0; i < inputs.length; i++) {
          inputs[i].readOnly = true;
          inputs[i].style.color = "rgba(51, 51, 51, 0.7)";
          inputs[i].style.fontWeight = "normal";
        }
      }
    </script>
  </div>
  <!--<script src="https://code.jquery.com/jquery.js"></script>
    <script src="../../js/refresh.js"></script>
    <script>
      RealTimeRefresh('/profile/admin/students_load.php', 'student-list');
    </script>-->
</body>

</html>