<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Szekrény beállítások</title>

    <?php
    include('admin_session_check.php'); // Itt hívjuk meg a session ellenőrzés fájlt
    ?>
    <style>
        .page0 {
            display: block;
        }

        .page1 {
            display: none;
        }
    </style>
</head>

<body>

    <div class="middle" style="background:transparent;box-shadow:none">

        <h1 class="font_bold" style="font-size:1.75rem;margin-top:15px;">Szekrény beállítások</h1>
        <h2 class="font_medium" style="font-size:0.85rem;color:rgba(0,0,0,0.5);">A szekrényeket kezelheted.</h2>

        <form class="bg" method="POST">
            <div class="stat-box">
                <div class="stat-content">
                    <div class="stat-value">Szekrények száma</div>
                    <div class="page0">
                        <p class="font_medium" style="font-size: 0.85rem; color: rgba(0, 0, 0, 0.5); color: #f77b72"><b>VIGYÁZZ!</b></p>
                        <h2 class="font_medium" style="font-size: 0.85rem; color: rgba(0, 0, 0, 0.5);">A meglévő szekrények törlődnek!</h2>
                        <p id="accepted" class="transparent stat-button" style="width:100px;text-align:center;">Rendben</p>
                    </div>
                    <div class="page1">
                        <div class="stat-label">Beállíthatod hány szekrény legyen elérhető.</div>
                        <input type="number" name="howmanylockerNumber" placeholder="Szekrények száma" class="input_style" style="width: 100%;margin-top: 15px;">
                        <input type="submit" name="saveLockerNumber" value="Mentés" class="transparent stat-button" style="width:100px;margin-top: 15px;">
                    </div>
                </div>
            </div>
            <div class="stat-box">
                <div class="stat-content">
                    <div class="stat-value">Nyitás mindet</div>
                    <div class="stat-label">Kinyithatod az összes szekrényt.</div>
                    <input type="submit" name="openAll" value="Nyitás mindet" class="transparent stat-button" style="margin-top: 15px;">
                </div>
            </div>

            <div class="stat-box">
                <div class="stat-content">
                    <div class="stat-value">Bizonyos nyitás</div>
                    <div class="stat-label">Add meg a kívánt szekrény számát a nyitáshoz.</div>
                    <input type="number" name="numberSelected" placeholder="Kívánt szekrény száma" class="input_style" style="width: 100%;margin-top: 15px;">
                    <input type="submit" name="openSelected" value="Nyitás" class="transparent stat-button" style="margin-top: 15px;">
                </div>
            </div>

            <div class="stat-box">
                <div class="stat-content">
                    <div class="stat-value">Fizikai szekrények</div>
                    <div class="stat-label" style="font-size:1.2em;"><?php echo phisycalLockersNumber(); echo " db"; ?></div>
                    <div class="stat-value">Virtuális</div>
                    <div class="stat-label" style="font-size:1.2em;"><?php echo virtualLockersNumber(); echo " db"; ?></div>
               </div>
            </div>
        </form>
        <script>
            document.getElementsByClassName("page0")[0].style.display = "block";
            document.getElementsByClassName("page1")[0].style.display = "none";

            document.getElementById("accepted").addEventListener("click", function() {
                document.getElementsByClassName("page0")[0].style.display = "none";
                document.getElementsByClassName("page1")[0].style.display = "block";
            });
        </script>
        <?php

        function deleteAndCreateNewOne()
        {
            global $conn;
            $lockernumber = intval($_POST['howmanylockerNumber']);
            $sql = "UPDATE settings SET value='$lockernumber' WHERE settingsName='NumberOfLockers'";
            $result = $conn->query($sql);
            if ($result) {
                $sql_delete = "DELETE FROM lockers";
                if ($conn->query($sql_delete) !== TRUE) {
                    echo "Hiba " . $conn->error;
                }

                for ($i = 1; $i <= $lockernumber; $i++) {
                    $sql_insert = "INSERT INTO lockers (id, status, NeptunCode, UniPassCode) VALUES ('$i', 'off', '', '')";
                    if ($conn->query($sql_insert) !== TRUE) {
                        echo "Hiba " . $conn->error;
                        break;
                    }
                }
                successMsg("Sikeres frissítés!", "Létrehoztál $lockernumber szekrényt.");
                echo '<script>
                                    window.setTimeout(function(){
                                        window.location.href = "../index.php";
                                    }, 2000);
                                    </script>';
                exit;
            }
        }


        function phisycalLockersNumber()
        {
            global $conn;
            $result = $conn->query("SELECT * FROM settings WHERE settingsName='PhysicalLockers'");
            $row = $result->fetch_assoc();
            $physicalLockers = $row['value'];
            return $physicalLockers;
        }

        function virtualLockersNumber(){
            global $conn;
            $result = $conn->query("SELECT * FROM settings WHERE settingsName='NumberOfLockers'");
            $row = $result->fetch_assoc();
            $virtualLockers = $row['value'];
            return $virtualLockers-phisycalLockersNumber();
        }

        function createLockers()
        {
            global $conn;
            $lockernumber = intval($_POST['howmanylockerNumber']);

            if (empty($lockernumber) || $lockernumber < phisycalLockersNumber()) {
                errorMsg("Hiba!", "Nem felel meg a szekrények száma! Legalább ".phisycalLockersNumber()." szekrény kell!");
                echo '<meta http-equiv="refresh" content="2">';
                exit;
            } else {
                /*  $sql_takenLockers = "SELECT * FROM led WHERE NeptunCode != ''";
                $result_takenLockers = $conn->query($sql_takenLockers);
                if ($result_takenLockers->num_rows > 0) {
                    //echo "Hiba: Már vannak kiosztott szekrények!";
                  
                    exit;
                } else {*/
                deleteAndCreateNewOne();
                //{
            }
        }


        function OpenAllandCloseAll($status)
        {
            global $conn;
            //update every locker status to off with sql query
            $sql = "UPDATE lockers SET status='$status'";
            $result = $conn->query($sql);
            if ($result) {
                //echo "Siker";
            } else {
                echo "Hiba: " . $conn->error;
            }
        }

        function openSelected()
        {
            global $conn;
            $numberSelected = intval($_POST['numberSelected']); // Convert input value to an integer

            $sql = "SELECT * FROM lockers WHERE id='$numberSelected'";
            $result = $conn->query($sql);

            if (empty($numberSelected)) {
                errorMsg("Hiba!", "Nem adtál meg szekrény számot.");
                echo '<meta http-equiv="refresh" content="2">';
                exit;
            } else if ($result->num_rows == 0) {
                errorMsg("Hiba!", "A megadott szekrény nem létezik.");
                echo '<meta http-equiv="refresh" content="2">';
                exit;
            } else {
                $sql = "UPDATE lockers SET status='on' WHERE id='$numberSelected'";
                $result = $conn->query($sql);
                if ($result) {
                    successMsg("Sikeres frissítés!", "Kinyitottad a(z) " . $numberSelected . ". szekrényt.");
                } else {
                    echo "Hiba: " . $conn->error;
                }
            }
        }

        function closeSelected()
        {
            global $conn;
            if (isset($_POST['openSelected'])) {
                $numberSelected = intval($_POST['numberSelected']); // Convert input value to an integer
                //update every locker status to on with sql query
                $sql = "UPDATE lockers SET status='off' WHERE id='$numberSelected'";
                if ($conn->query($sql) !== TRUE) {
                    echo "Hiba: " . $conn->error;
                } else {
                    //echo "Sikeres zárva.";
                }
            }
        }


        if (isset($_POST['saveLockerNumber'])) {
            createLockers();
        }

        if (isset($_POST['openAll'])) {
            successMsg("Sikeres frissítés!", "Kinyitottad az összes szekrényt!");
            OpenAllandCloseAll("on");
            usleep(600000);
            OpenAllandCloseAll("off");
            echo '<script>
                    window.setTimeout(function(){
                        window.location.href = "lockernumber.php";
                    }, 2000);
                    </script>';
        }

        if (isset($_POST['openSelected'])) {
            successMsg("Sikeres frissítés!", "Kinyitottad a(z) " . $_POST["numberSelected"] . ". szekrényt.");
            openSelected();
            usleep(600000);
            closeSelected();
            echo '<script>
                    window.setTimeout(function(){
                        window.location.href = "lockernumber.php";
                    }, 2000);
                    </script>';
        }


        $conn->close();
        ?>
    </div>
</body>

</html>