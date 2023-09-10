<?php

include('../session_check.php');
include('../apikeyfunction.php');
include('../../connection.php');
include('../header.php');

//ADATOK MEGTISZTITASA ELLENORZESE

if (isset($_POST['update'])) {
    $id = $_SESSION['id'];
    $vname = mysqli_real_escape_string($conn, trim($_POST['VName']));
    $kname = mysqli_real_escape_string($conn, trim($_POST['KName']));
    $email = mysqli_real_escape_string($conn, trim($_POST['Email']));
    $password = mysqli_real_escape_string($conn, trim($_POST['Password']));
    $passwordAgain = mysqli_real_escape_string($conn, trim($_POST['PasswordAgain']));
    $neptuncode = $_SESSION['neptuncode'];
    $unipasscode = mysqli_real_escape_string($conn, trim($_POST['UniPassCode']));

    
    if (empty($vname) || empty($kname) || empty($email) || empty($password) || empty($unipasscode)) {
        errorMsg("Hiba!", "Nem lehet üres mező!");
        header("Refresh: 2; url=../../index.php");
        exit;
    }


    if ($unipasscode == "ERROR") {
        errorMsg("Sikertelen frissítés!", "Hibás UniPass!");
        header("Refresh: 2; url=../../index.php");
        exit;
    }

    //check the unipass code already exist
    $sql = "SELECT * FROM users WHERE UniPassCode='$unipasscode' AND UniPassCode != '' AND id != '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    if (is_array($row)) {
        errorMsg("Sikertelen frissítés!", "Ez az UniPass kód már foglalt!");
        header("Refresh: 2; url=../../index.php");
        exit;
    }



    $sql = "UPDATE users SET VName='$vname', KName='$kname', Email='$email', Password='$password', NeptunCode='$neptuncode', UniPassCode='$unipasscode' WHERE id='$id'";
    $result = mysqli_query($conn, $sql);


    // Check if any field is updated, and if the NeptunCode exists in the "led" table
    /* if ($result && (!empty($vname) || !empty($kname) || !empty($email) || !empty($password) || !empty($neptuncode) || !empty($unipasscode))) {
        $sql = "SELECT id, status FROM lockers WHERE NeptunCode = '$neptuncode'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Update the API with UniPassCode only
                $json = file_get_contents('http://api.toxy.hu/update.php?id='.$row["id"].'&status=off&NeptunCode='.$neptuncode.'&UniPassCode='.$unipasscode.'&apikey='.getApiKey().'');
                $obj = json_decode($json);
            }
        }
        successMsg("Sikeres frissítés!", "Jelentkezz be újra!");
        header("Refresh: 2; url=../../index.php");
        exit;
    } else if (empty($vname) || empty($kname) || empty($email) || empty($password) || empty($neptuncode) || empty($unipasscode)) {
        successMsg("Nem történt módosítás!", "Az adataid nem változtak.");
        header("Refresh: 2; url=../../index.php");
        exit;
    } else {
        errorMsg("Sikertelen frissítés!", "Nem sikerült frissíteni az adataidat.");
        header("Refresh: 2; url=../../index.php");
        exit;
    }*/

    //check if any field is updated

    if ($result && (!empty($vname) || !empty($kname) || !empty($email) || !empty($password) || !empty($neptuncode) || !empty($unipasscode))) {
        $sql = "SELECT id, status FROM lockers WHERE NeptunCode = '$neptuncode'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Update the API with UniPassCode only
                $json = file_get_contents('http://api.toxy.hu/update.php?id=' . $row["id"] . '&status=off&NeptunCode=' . $neptuncode . '&UniPassCode=' . $unipasscode . '&apikey=' . getApiKey() . '');
                $obj = json_decode($json);
            }
        }
        successMsg("Sikeres frissítés!", "Jelentkezz be újra!");
        header("Refresh: 2; url=../../index.php");
        exit;
    } else if (empty($vname) || empty($kname) || empty($email) || empty($password) || empty($neptuncode) || empty($unipasscode)) {
        successMsg("Nem történt módosítás!", "Az adataid nem változtak.");
        header("Refresh: 2; url=../../index.php");
        exit;
    } else {
        errorMsg("Sikertelen frissítés!", "Nem sikerült frissíteni az adataidat.");
        header("Refresh: 2; url=../../index.php");
        exit;
    }
}

if (isset($_POST['delete'])) {
    $neptuncode = strtoupper(mysqli_real_escape_string($conn, $_POST['NeptunCode']));
    $id = $_SESSION['id'];

    // Check if the NeptunCode exists in the "led" table
    $sql = "SELECT id, NeptunCode FROM lockers WHERE NeptunCode = '$neptuncode'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Update the API with status "off" and empty NeptunCode and UniPassCode
            $json = file_get_contents('http://api.toxy.hu/update.php?id=' . $row["id"] . '&status=off&NeptunCode=&UniPassCode=&apikey=' . getApiKey() . '');
            $obj = json_decode($json);
        }
    }

    $sql = "DELETE FROM users WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        successMsg("Sikeres törlés!", "Sikeresen törölted a fiókodat!");
        header("Refresh: 2; url=../../index.php");
        exit;
    } else {
        errorMsg("Sikertelen törlés!", "Nem sikerült törölni a fiókodat.");
    }
}
$conn->close();
exit();
