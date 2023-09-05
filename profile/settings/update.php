<?php
session_start();
include('../../connection.php');
include('../header.php');
include('../apikeyfunction.php');

if (isset($_POST['update'])) {
    $id = $_SESSION['id'];
    $vname = mysqli_real_escape_string($conn, trim($_POST['VName']));
    $kname = mysqli_real_escape_string($conn, trim($_POST['KName']));
    $email = mysqli_real_escape_string($conn, trim($_POST['Email']));
    $password = mysqli_real_escape_string($conn, trim($_POST['Password']));
    $neptuncode = mysqli_real_escape_string($conn, trim($_POST['NeptunCode']));
    $unipasscode = mysqli_real_escape_string($conn, trim($_POST['UniPassCode']));

    
    
    $sql = "SELECT id FROM users WHERE NeptunCode = '$neptuncode' AND id != '$id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        errorMsg("Sikertelen frissítés!", "Ez a Neptun kód már foglalt!");
        header("Refresh: 2; url=index.php");
        exit;
    }
    
    $sql = "UPDATE users SET VName='$vname', KName='$kname', Email='$email', Password='$password', NeptunCode='$neptuncode', UniPassCode='$unipasscode' WHERE id='$id'";
    $result = mysqli_query($conn, $sql);

  
    // Check if any field is updated, and if the NeptunCode exists in the "led" table
    if ($result && (!empty($vname) || !empty($kname) || !empty($email) || !empty($password) || !empty($neptuncode) || !empty($unipasscode))) {
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
        header("Refresh: 2; url=../../index.html");
        exit;
    } else if (empty($vname) || empty($kname) || empty($email) || empty($password) || empty($neptuncode) || empty($unipasscode)) {
        successMsg("Nem történt módosítás!", "Az adataid nem változtak.");
    } else {
        errorMsg("Sikertelen frissítés!", "Nem sikerült frissíteni az adataidat.");
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
        header("Refresh: 2; url=../../index.html");
        exit;
    } else {
        errorMsg("Sikertelen törlés!", "Nem sikerült törölni a fiókodat.");
    }
}
$conn->close();
exit();
?>
