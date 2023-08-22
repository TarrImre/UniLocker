<?php
// (C) SET LANGUAGE INTO SESSION
session_start();
if (!isset($_SESSION["lang"])) {
    $_SESSION["lang"] = "hu";
}
if (isset($_POST["lang"])) {
    $_SESSION["lang"] = $_POST["lang"];
    header("Refresh:0");
    exit;
}

// (D) LOAD LANGUAGE FILE
require "lang/lang-" . $_SESSION["lang"] . ".php"; ?>
<!DOCTYPE HTML>
<html lang="<?php echo $_SESSION["lang"] ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon.ico">

    <!-- Add to home screen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="UniPost">
    <link rel="apple-touch-icon" href="icons/icon-152x152_V2.png">

    <link rel="stylesheet" href="css/login.css">

    <title><?= $_TXT[0] ?></title>

</head>

<body id="body">
    <div class="screen-1">
        <div class="middle">
            <h1><?= $_TXT[1] ?></h1>
        </div>
        <form action="login.php" method="POST">
            <div class="email" style="margin-bottom:15px;">
                <input type="text" name="NeptunCode" placeholder="<?= $_TXT[2] ?>" autocomplete="off">
            </div>
            <div class="password" style="margin-bottom:15px;margin-top:15px;">
                <input type="password" name="Password" placeholder="<?= $_TXT[3] ?>" autocomplete="off">
            </div>

            <button class="login" name="submit" style="width:100%;"><?= $_TXT[4] ?></button>
        </form>
        <div class="footer">
            <a class="register a_style" style="margin:5px;" href="register.php"><?= $_TXT[5] ?></a>
            <a class="register a_style" style="margin:5px;" href="pwa/"><?= $_TXT[6] ?></a>
        </div>
        <div class="middle">
            <form method="post">
                <button class="languageButton" type="submit" name="lang" value="hu"><img src="icons/flags/hungary.jpg" alt="Hungarian" title="Hungarian"></button>
                <button class="languageButton" type="submit" name="lang" value="en"><img src="icons/flags/english.jpg" alt="English" title="English"></button>
                <button class="languageButton" type="submit" name="lang" value="de"><img src="icons/flags/germany.jpg" alt="Germany" title="Germany"></button>
                <button class="languageButton" type="submit" name="lang" value="zh"><img src="icons/flags/china.jpg" alt="Chinese" title="Chinese"></button>
                <button class="languageButton" type="submit" name="lang" value="ja"><img src="icons/flags/japan.jpg" alt="Japanese" title="Japanese"></button>
                <button class="languageButton" type="submit" name="lang" value="ar"><img src="icons/flags/arab.jpg" alt="Arabic" title="Arabic"></button>
            </form>
            <!--<button class="languageButton"><img src="icons/flags/hungary.jpg" alt="Hungarian" title="Hungarian"></button>
                <button class="languageButton"><img src="icons/flags/english.jpg" alt="English" title="English"></button>
                <button class="languageButton"><img src="icons/flags/germany.jpg" alt="Germany" title="Germany"></button>
                <button class="languageButton"><img src="icons/flags/china.jpg" alt="Chinese" title="Chinese"></button>
                <button class="languageButton"><img src="icons/flags/japan.jpg" alt="Japanese" title="Japanese"></button>
                <button class="languageButton"><img src="icons/flags/arab.jpg" alt="Arabic" title="Arabic"></button>-->
        </div>
    </div>
    <div class="left-circle"></div>
    <!--<div class="right-circle"></div>-->
</body>

</html>