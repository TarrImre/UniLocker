<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API</title>

    <?php
    include('admin_session_check.php'); // Itt hívjuk meg a session ellenőrzés fájlt
    ?>
    <style>
        .transparent.stat-button {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease;
        }

        /*rotate the icon while holding the button*/
        .transparent.stat-button i {
            transition: transform 0.3s ease;
        }

        .transparent.stat-button:hover i {
            transform: rotate(360deg);
            transition: transform 0.3s ease;
        }

        .transparent.stat-button:hover {
            transform: scale(1.1);
            transition: transform 0.3s ease;
        }
    </style>
</head>

<body>
    <div class="middle" style="background:transparent;box-shadow:none;">
        <h1 class="font_bold" style="font-size:1.75rem;margin-top:15px;">API</h1>
        <!--<h2 class="font_medium" style="font-size:0.85rem;color:rgba(0,0,0,0.5);">API kezelés</h2>-->
        <div class="bg">
            <div class="stat-box" style="margin: 10px;">
                <div class="box">
                    <div class="stat-content" style="margin-left:-15px;">
                        <div class="stat-title" style="color: #0056b3;font-size:20px;margin-bottom:0px;">Titkosított API kulcs:</div>
                        <div class="stat-label" style="font-size:0.85rem;margin-top:10px;"><?php echo getApiKey(); ?></div>
                        <a style="color:#888888;" href="https://api.toxy.hu/read_all.php?id=1&apikey=<?php echo getApiKey(); ?>"><i class='bx bx-link'></i></a>
                        <br>
                        <div class="stat-label" style="font-size:0.85rem;margin-top:20px;">ESP Json</div>
                        <a style="color:#888888;" href="https://api.toxy.hu/read_api.php?esp"><i class='bx bx-link'></i></a>
                        <br>
                        <!--make a button to random generate a new api key-->
                        <form method="POST" style="margin-top:20px;">
                            <p style="color: #0056b3;">Új kulcs generálása:</p>
                            <button type="submit" name="apiKeyGenerateButton" value="Új kulcs generálása" class="transparent stat-button" style="margin-top: 15px;font-size:30px;padding:0px">
                                <i class='bx bxs-analyse rotate' style="font-size: 30px;padding: 10px;"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php

    function getApiKey()
    {
        global $conn;
        $sql = "SELECT * FROM settings WHERE settingsName = 'ApiKey'";
        $result = $conn->query($sql);
        $row = mysqli_fetch_array($result);
        if (is_array($row)) {
            $ApiKey = $row['value'];
            return $ApiKey;
        }
        return null;
    }


    function randomGen($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++)
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        return $randomString;
    }

    if (isset($_POST['apiKeyGenerateButton'])) {
        questionMsg("A művelet nem visszavonható!", "Biztos vagy benne?","api.php?generate=true");
    }

    if (isset($_GET['generate'])) {
        if ($_GET['generate'] == "true") {
            generateApiKey();
        }
    }

    function generateApiKey()
    {
        global $conn;
        $apiKey = md5(randomGen(32));
        $sql = "UPDATE settings SET value='$apiKey' WHERE settingsName='ApiKey'";
        $result = $conn->query($sql);
        if ($result) {
            echo "<meta http-equiv='refresh' content='0;url=api.php'>";
        } else {
            echo "Hiba: " . $conn->error;
        }
    }

    ?>
</body>

</html>