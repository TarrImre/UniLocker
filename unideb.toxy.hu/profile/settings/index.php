<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil beállítások</title>

  <?php
  include('../session_check.php');
  include('../../connection.php');
  include('../header.php');
  include('../nav.php');
  include('../apikeyfunction.php');
  ?>
  <style>
    .page2 {
      display: none;
    }

    #vissza,
    #deactivate,
    .status {
      display: none;
    }

    .circle-container {
      text-align: center;
      display: flex;
      align-items: center;
      /* Középre igazítás függőlegesen */
      justify-content: center;
      /* Középre igazítás vízszintesen */
      margin-top: 60px;
      margin-bottom: 70px;
    }

    .circle {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: #1dbef9;
      position: absolute;
      /* Abszolút pozíció a konténerhez viszonyítva */
      animation: mymove 6s infinite;
      animation-delay: calc(var(--delay) * -1s);
      /* Késleltetés beállítása */
    }

    .circle:nth-child(1) {
      --delay: 0;
      /* 0 másodperc késleltetés az első körhöz */
    }

    .circle:nth-child(2) {
      --delay: 1;
      /* 2 másodperc késleltetés a második körhöz */
    }

    .circle:nth-child(3) {
      --delay: 2;
      /* 4 másodperc késleltetés a harmadik körhöz */
    }

    @keyframes mymove {
      0% {
        opacity: 1;
      }

      100% {
        width: 250px;
        height: 250px;
        opacity: 0;
      }
    }

    .arrows {
      cursor: pointer;
    }

    .deactivate {
      width: 200px;
      padding: 12px;
      margin-top: 10px;
      background-color: #ff9999;
      color: #8f0000;
      margin-bottom: 10px;
    }

    .deactivate:hover {
      background-color: #ffcccc;
    }
  </style>
</head>

<body>
  <?php
  
  $sql = "SELECT * FROM users WHERE id='$_SESSION[id]'";
  $result = $conn->query($sql);
  $row = mysqli_fetch_array($result);
  if (is_array($row)) {
    $id = $row['id'];
    $vname = $row['VName'];
    $kname = $row['KName'];
    $email = $row['Email'];
    $password = $row['Password'];
    $unipasscode = $row['UniPassCode'];
  }



  include('../../input_validation.php');

  if (isset($_POST['update'])) {
    $vname = htmlspecialchars(mb_convert_case(trim($_POST['VName']), MB_CASE_TITLE, 'UTF-8'));
    $kname = htmlspecialchars(mb_convert_case(trim($_POST['KName']), MB_CASE_TITLE, 'UTF-8'));
    $email = htmlspecialchars(trim(strtolower($_POST['Email'])));
    $oldpassword = trim($_POST['OldPassword']);
    $newpassword = trim($_POST['NewPassword']);
    $unipasscode = htmlspecialchars(trim($_POST['UniPassCode']));
    $neptuncode = $_SESSION['neptuncode'];

    $vnameErr = validateVName($vname);
    $knameErr = validateKName($kname);
    $emailErr = validateEmail($email);
    $unipasscodeErr = validateUniPassCode($unipasscode);

    $vname = mysqli_real_escape_string($conn, $vname);
    $kname = mysqli_real_escape_string($conn, $kname);
    $email = mysqli_real_escape_string($conn, $email);
    $unipasscode = mysqli_real_escape_string($conn, $unipasscode);


    //check if the email is already in use by another user
    $sql = "SELECT * FROM users WHERE Email='$email' AND Email != '' AND id != '$id'";
    $result = mysqli_query($conn, $sql);
    $emailExistByAnotherUser = mysqli_fetch_array($result);



    $sql = "SELECT * FROM users WHERE UniPassCode='$unipasscode' AND UniPassCode != '' AND id != '$id'";
    $result = mysqli_query($conn, $sql);
    $unipassExistByAnotherUser = mysqli_fetch_array($result);

    if ($vnameErr) {
      errorMsg("Hibás Vezetéknév!", $vnameErr);
    } else if ($knameErr) {
      errorMsg("Hibás Keresztnév!", $knameErr);
    } else if ($emailErr) {
      errorMsg("Hibás Email!", $emailErr);
    } else if (is_array($emailExistByAnotherUser)) {
      errorMsg("Sikertelen frissítés!", "Ez az email cím már foglalt!");
    } else if ($unipasscodeErr) {
      errorMsg("Hibás UniPass!", $unipasscodeErr);
    } else if (is_array($unipassExistByAnotherUser)) {
      errorMsg("Sikertelen frissítés!", "Ez az UniPass kód már foglalt!");
    } else {
      if (!empty($oldpassword) || !empty($newpassword)) {
        
        if (!password_verify($oldpassword, $password)) {
          errorMsg("Hibás régi jelszó!", "A régi jelszó nem egyezik!");
        } else if (strlen($newpassword) < 8) {
          errorMsg("Hibás jelszó!", "Az új jelszó túl rövid! Minimum 8 karakter!");
        } else {
          $passwordToUpdate = !empty($newpassword) ? $newpassword : $password;
          $passwordToUpdate = password_hash($passwordToUpdate, PASSWORD_DEFAULT);
          updateProfile($vname, $kname, $email, $passwordToUpdate, $_SESSION['neptuncode'], $unipasscode);

          $sql = "SELECT id, status FROM lockers WHERE NeptunCode = '$neptuncode'";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              // Update the API with UniPassCode only
              $json = file_get_contents('http://api.toxy.hu/update.php?id=' . $row["id"] . '&status=off&NeptunCode=' . $neptuncode . '&UniPassCode=' . $unipasscode . '&apikey=' . getApiKey() . '');
              $obj = json_decode($json);
            }
          }
          echo "<meta http-equiv='refresh' content='2; url=../../index.php'>";
          session_destroy();
        }
      } else {
        updateProfile($vname, $kname, $email, $password, $_SESSION['neptuncode'], $unipasscode);
        $neptuncode = $_SESSION['neptuncode'];
        $sql = "SELECT id, status FROM lockers WHERE NeptunCode = '$neptuncode'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            // Update the API with UniPassCode only
            $json = file_get_contents('http://api.toxy.hu/update.php?id=' . $row["id"] . '&status=off&NeptunCode=' . $neptuncode . '&UniPassCode=' . $unipasscode . '&apikey=' . getApiKey() . '');
            $obj = json_decode($json);
          }
        }
        echo "<meta http-equiv='refresh' content='2; url=../../index.php'>";
        session_destroy();
      }
    }
  }



  if (isset($_POST['delete'])) {
    $id = $_SESSION['id'];
    questionMsg('Biztosan törölni szeretnéd?', 'A művelet nem vonható vissza!', 'index.php?id=' . $id . '');
  }

  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM users WHERE id='$id'";
    $neptuncode = $_SESSION['neptuncode'];
    $query_run = mysqli_query($conn, $sql);

    if ($query_run) {
      $sql = "SELECT id, NeptunCode FROM lockers WHERE NeptunCode = '$neptuncode'";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          // Update the API with status "off" and empty NeptunCode and UniPassCode
          $json = file_get_contents('http://api.toxy.hu/update.php?id=' . $row["id"] . '&status=off&NeptunCode=&UniPassCode=&apikey=' . getApiKey() . '');
          $obj = json_decode($json);
        }
      }
      echo "<meta http-equiv='refresh' content='0; url=../../index.php'>";
      session_destroy();
    } else {
      errorMsg("Sikertelen törlés!", "Valami hiba történt!");
    }
  }




  if (isset($_POST['deactivate'])) {
    // update in the database the UniPassCode to deactivated
    $sql = "UPDATE users SET UniPassCode='DEACTIVATED' WHERE id='{$_SESSION['id']}'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
      $neptuncode = $_SESSION['neptuncode'];
      $sql = "SELECT id, NeptunCode FROM lockers WHERE NeptunCode = '$neptuncode'";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          // Update the API with status "off" and empty NeptunCode and UniPassCode
          $json = file_get_contents('http://api.toxy.hu/update.php?id=' . $row["id"] . '&status=off&NeptunCode=' . $neptuncode . '&UniPassCode=DEACTIVATED&apikey=' . getApiKey() . '');
          $obj = json_decode($json);
        }
      }
      successMsg('UniPass kártya deaktiválva!', 'Jelentkezz be újra!');
      echo "<meta http-equiv='refresh' content='2; url=../../index.php'>";
      session_destroy();
    } else {
      echo '<p style="margin-bottom:10px;">Sikertelen törlés</p>';
    }
  }


  function updateProfile($vname, $kname, $email, $password, $neptunCode, $unipasscode)
  {
    global $conn;
    $sql = "UPDATE users SET VName='$vname', KName='$kname', Email='$email', Password='$password', NeptunCode='$neptunCode', UniPassCode='$unipasscode' WHERE id='{$_SESSION['id']}'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
      successMsg("Sikeres frissítés!", "Jelentkezz be újra!");
    } else {
      errorMsg("Sikertelen frissítés!", "Valami hiba történt!");
    }
  }

  function cardStatus(){
    global $unipasscode;
    if($unipasscode == "DEACTIVATED"){
      return "UniPass kártya deaktiválva!";
    }else if($unipasscode == "ERROR"){
      return "Nincs UniPass kártya!";
    }else{
      return "UniPass kártya aktiválva!";
    }
  }

  $conn->close();
  ?>
  <div class="middle" style="width: 320px;opacity: 0.95;">
    <h1 class="font_bold" style="font-size:1.75rem;margin-top:10px;">Profil beállítások</h1>
    <div class="status">
      <p style="margin-bottom:10px;"><?php echo cardStatus() ?></p>
    </div>
    <form method="POST">
      <div class="input_box">
        <div class="page1">
          <div class="box">
            <p>Vezetéknév</p>
            <input class="input_style" type="text" name="VName" value="<?php echo $vname ?>">
          </div>
          <div class="box">
            <p>Keresztnév</p>
            <input class="input_style" type="text" name="KName" value="<?php echo $kname ?>">
          </div>
          <div class="box">
            <p>Email</p>
            <input class="input_style" type="text" name="Email" value="<?php echo $email ?>">
          </div>
          <div class="box">
            <p>Régi jelszó</p>
            <input class="input_style" type="password" name="OldPassword" value="">
          </div>
          <div class="box">
            <p>Új jelszó</p>
            <input class="input_style" type="password" name="NewPassword" value="">
          </div>
        </div>

        <div class="page2">
          <div class="unipassnotAvailable">
            <div class="input_style" style="background-color: #ff9999;color: #8f0000;margin-top:15px;margin-bottom:30px;">
              <p>Sajnáljuk, az UniPass regisztráció nem elérhető!</p>
            </div>
          </div>
          <div class="unipassisAvailable">
            <div class="box">
              <div class="circle-container" style="margin-top: 120px;">
                <div class="circle"></div>
                <div class="circle"></div>
                <div class="circle"></div>
                <p id="scanButton" style="z-index:1;text-align:center;padding:5px;font-size:25px;color:white;"><i class='bx bxs-hand-up'></i></p>
              </div>
              <p id="randomtext" style="text-align:center;color:#888888;margin-top:130px;margin-bottom:20px;padding-top:0px;">Nyomd meg középen, majd tartsd a kártyát a telefon hátuljához!</p>
              <input type="text" id="Unipass" name="UniPassCode" autocomplete="off" style="display: none;" placeholder="Unipass kártya" value="<?php echo $unipasscode ?>" readonly>
            </div>
          </div>
        </div>
      </div>
      <button type="button" id="showUniPass" class="button" style="margin-top: 5px;margin-bottom:5px;"><b>UniPass Kártya</b></button>
      <button type="button" id="vissza" style="margin-bottom:20px;" class="button"><b>Vissza</b></button>
      <button id="deactivate" name="deactivate" class="button deactivate"><b>Kártya letiltása</b></button>

      <input type="hidden" name="post_data" value="1">
      <div style="margin-bottom:15px;" class="gombok">
        <button class="transparent stat-button" type="submit" name="update">Frissítés</button>
        <button class="transparent stat-button" type="submit" name="delete">Fiók törlése</button>
      </div>
    </form>
    <!--UNIPASS REGISTERED ÁLLAPOT JELZŐ-->
    <script src="../../js/nfc.js"></script>
    <script>
      const page1 = document.querySelector(".page1");
      const page2 = document.querySelector(".page2");
      const showUniPass = document.querySelector("#showUniPass");
      const vissza = document.querySelector("#vissza");
      const unipassisAvailable = document.querySelector(".unipassisAvailable");
      const unipassnotAvailable = document.querySelector(".unipassnotAvailable");

      showUniPass.addEventListener("click", () => {
        page1.style.display = "none";
        page2.style.display = "block";
        showUniPass.style.display = "none";
        vissza.style.display = "inline-block";
        document.querySelector("#deactivate").style.display = "inline-block";
        document.querySelector(".gombok").style.display = "none";
        document.querySelector(".status").style.display = "block";
      });
      vissza.addEventListener("click", () => {
        page1.style.display = "block";
        page2.style.display = "none";
        showUniPass.style.display = "inline-block";
        vissza.style.display = "none";
        document.querySelector("#deactivate").style.display = "none";
        document.querySelector(".gombok").style.display = "block";
        document.querySelector(".status").style.display = "none";
      });

      const isMobile = navigator.userAgentData.mobile;
      if (!isMobile) {
        unipassisAvailable.style.display = "none";
        unipassnotAvailable.style.display = "block";
      } else {
        unipassisAvailable.style.display = "block";
        unipassnotAvailable.style.display = "none";
      }
    </script>
  </div>


</body>

</html>