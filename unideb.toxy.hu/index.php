<?php
/*$time = 2 * 60 * 60;
session_set_cookie_params($time);*/
session_start();
?>
<!DOCTYPE HTML>
<html lang="">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="pwa/icons/favicon.ico">

    <!-- Add to home screen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="UniPost">
    <link rel="apple-touch-icon" href="icons/icon-152x152_V2.png">


    <link rel="stylesheet" href="scss/css/login.css">

    <title>Bejelentkezés</title>

</head>

<body>
    <?php
    include('connection.php');
    include('profile/sweetalert.php');
    if (isset($_POST['submit'])) {
        $NeptunCode = strtoupper(str_replace(' ', '', $_POST['NeptunCode']));
        $Password = $_POST['Password'];

        //htmlspecialchars
        $NeptunCode = htmlspecialchars($NeptunCode);
        $Password = htmlspecialchars($Password);

        //mysqli_real_escape_string
        $NeptunCode = mysqli_real_escape_string($conn, $NeptunCode);
        $Password = mysqli_real_escape_string($conn, $Password);

        if (empty($NeptunCode) || empty($Password)) {
            errorMsg("Minden mező kötelező!", "Próbáld újra!");
        } else if (strlen($NeptunCode) != 6) {
            errorMsg("Hibás Neptun kód!", "Próbáld újra!");
        } else {
            $sql = "SELECT * FROM users WHERE NeptunCode='$NeptunCode'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_array($result);
            if (is_array($row)) {
                if (password_verify($Password, $row['Password'])) {
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['vname'] = $row['VName'];
                    $_SESSION['kname'] = $row['KName'];
                    $_SESSION['email'] = $row['Email'];
                    $_SESSION['neptuncode'] = $row['NeptunCode'];
                    $_SESSION['UniPassCode'] = $row['UniPassCode'];
                    $_SESSION['Rank'] = $row['Rank'];

                    //echo "sikeres!";
                    header("Location: profile/");
                    exit;
                } else {
                    errorMsg("Hibás Kód vagy jelszó!", "Próbáld újra!");
                }
            } else {
                errorMsg("Hibás Kód vagy jelszó!", "Próbáld újra!");
            }
        }
    }
    ?>
    <div class="container">
        <div class="screen-1">
            <div class="middle">
                <h1 style="margin-bottom: 30px;">Bejelentkezés</h1>
            </div>
            <form method="POST">
                <div class="input_style" style="margin-bottom:15px;">
                    <input type="text" name="NeptunCode" placeholder="Neptun kód" autocomplete="off">
                </div>
                <div class="input_style" style="margin-bottom:15px;margin-top:15px;">
                    <input type="password" name="Password" placeholder="Jelszó" autocomplete="off">
                </div>

                <button class="login" name="submit" style="width:100%;">Belépés</button>
            </form>
            <div class="footer">
                <a class="register a_style" style="margin:5px;font-size:13px;" href="register.php">Regisztráció</a>
                <a class="register a_style" style="margin:5px;font-size:13px;" href="pwa/" class="pwa">Telepítő</a>
            </div>
            <div class="middle"  style="margin-top: -80px;">
                <p class="footer__text" style="opacity: 0.3;">© <?= date("Y") ?> UniLocker</p>
            </div>
        </div>
    </div>
    <div class="left-circle"></div>
    <!--<div class="right-circle"></div>-->

</body>

</html>