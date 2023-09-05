<?php
/*$time = 2 * 60 * 60;
session_set_cookie_params($time);*/
session_start()
?>
<?php
    include('connection.php');
    include('profile/sweetalert.php');
    if (isset($_POST['submit'])) {
        $NeptunCode = strtoupper(str_replace(' ', '', $_POST['NeptunCode']));
        $Password = $_POST['Password'];

        $msg = "";
        if (empty($NeptunCode) || empty($Password)) {
            $msg = "Minden mező kötelező!";
        } else {
            $sql = "SELECT * FROM users WHERE NeptunCode='$NeptunCode' AND Password='$Password'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_array($result);
            if (is_array($row)) {
                $_SESSION['id'] = $row['id'];
                $_SESSION['vname'] = $row['VName'];
                $_SESSION['kname'] = $row['KName'];
                $_SESSION['email'] = $row['Email'];
                $_SESSION['neptuncode'] = $row['NeptunCode'];
                $_SESSION['UniPassCode'] = $row['UniPassCode'];
                $_SESSION['Rank'] = $row['Rank'];

                $msg = "Sikeres!";
                header("Location: profile/");
                exit;
            } else {
                $msg = "Hibás Kód vagy jelszó!";
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    body{
        background-color: #DDE5F4;
        font-family: 'Poppins', sans-serif;
    }
    </style>
</head>
<body>
<?php
    if (isset($_POST['submit'])) {
        errorMsg($msg,"Próbáld újra");
        header("Refresh: 2; url=index.php");
    }
    ?>   
</body>
</html>
