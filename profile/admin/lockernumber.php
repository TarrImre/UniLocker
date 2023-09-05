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
            .page1{
                display: none;
            }
        </style>
</head>

<body>

    <div class="middle">

        <h1 class="font_bold" style="font-size:1.75rem;margin-top:15px;">Lockers number</h1>
        <h2 class="font_medium" style="font-size:0.85rem;color:rgba(0,0,0,0.5);">You can set how many lockers you want</h2>

        <form class="bg" method="POST">
            <div class="stat">
                <div class="lockernumber_panels">
                    <h1 class="font_bold" style="font-size: 1.75rem;color: black">Szekrények száma</h1>
                    <div class="page0">
                        <p class="font_medium" style="font-size: 0.85rem; color: rgba(0, 0, 0, 0.5); color: #f77b72"><b>VIGYÁZZ!</b></p>
                        <h2 class="font_medium" style="font-size: 0.85rem; color: rgba(0, 0, 0, 0.5); color: white">A meglévő, foglalt szekrények törlődnek!</h2>
                        <p id="accepted">Rendben.</p>
                    </div>
                    <div class="page1">
                        <h2 class="font_medium" style="font-size: 0.85rem; color: rgba(0, 0, 0, 0.5); color: white">Beállíthatod hány szekrény legyen elérhető</h2>
                        <input type="number" name="howmanylockerNumber" placeholder="Lockers number" class="input" style="width: 100%;margin-top: 15px;">
                        <input type="submit" name="saveLockerNumber" value="Save" class="button" style="width: 100%;margin-top: 15px;">
                    </div>
                </div>
            </div>
            <div class="stat">
                <div class="lockernumber_panels">
                    <h1 class="font_bold" style="font-size: 1.75rem;color: black">Nyitás mindet</h1>
                    <h2 class="font_medium" style="font-size: 0.85rem; color: rgba(0, 0, 0, 0.5); color: white">Kinyithatod az összes szekrényt</h2>
                    <input type="submit" name="openAll" value="Nyitás mindet" class="button" style="margin-top: 15px;">
                </div>
            </div>
            <div class="stat">
                <div class="lockernumber_panels">
                    <h1 class="font_bold" style="font-size: 1.75rem;color: black">Bizonyos nyitás</h1>
                    <h2 class="font_medium" style="font-size: 0.85rem; color: rgba(0, 0, 0, 0.5); color: white">Add meg a kívánt szekrény számát a nyitáshoz.</h2>
                    <input type="number" name="numberSelected" placeholder="Lockers number" class="input" style="width: 100%;margin-top: 15px;">
                    <input type="submit" name="openSelected" value="Nyitás" class="button" style="margin-top: 15px;">
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

        function createLockers()
        {
            global $conn;
            $lockernumber = intval($_POST['howmanylockerNumber']);
            if (empty($lockernumber) || $lockernumber < 1) {
                errorMsg("Hiba!", "Nem felel meg a szekrények száma!");
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
                exit;
            } else if ($result->num_rows == 0) {
                errorMsg("Hiba!", "A megadott szekrény nem létezik.");
                exit;
            } else {
                $sql = "UPDATE lockers SET status='on' WHERE id='$numberSelected'";
                $result = $conn->query($sql);
                if ($result) {
                    successMsg("Sikeres frissítés!", "Kinyittotad a(z) " . $numberSelected . ". szekrényt.");
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
            usleep(2000000);
            OpenAllandCloseAll("off");
            echo '<script>
                    window.setTimeout(function(){
                        window.location.href = "lockernumber.php";
                    }, 2000);
                    </script>';
        }

        if (isset($_POST['openSelected'])) {
            successMsg("Sikeres frissítés!", "Kinyittotad a(z) " . $_POST["numberSelected"] . ". szekrényt.");
            openSelected();
            usleep(2000000);
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