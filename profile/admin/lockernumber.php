<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    // Enable error reporting to see any PHP errors
    //error_reporting(E_ALL);
    //ini_set('display_errors', 1);

    session_start();

    if (isset($_SESSION['neptuncode'])) {
        include('../../connection.php');
        include('../header.php');
    ?>

        <?php include('../nav.php'); ?>
        <div class="middle">

            <h1 class="font_bold" style="font-size:1.75rem;margin-top:15px;">Lockers number</h1>
            <h2 class="font_medium" style="font-size:0.85rem;color:rgba(0,0,0,0.5);">You can set how many lockers you want</h2>

            <form class="bg" method="POST">
                <input type="number" name="lockernumber" placeholder="Lockers number" class="input" style="width: 100%;margin-top: 15px;">
                <input type="submit" name="saveLockerNumber" value="Save" class="button" style="width: 100%;margin-top: 15px;">
            </form>

            <?php
            if (isset($_POST['saveLockerNumber'])) {
                if (isset($_POST['lockernumber'])) {
                    if (empty($_POST['lockernumber'])) {
                        echo "Please enter a number.";
                        exit;
                    } else if (intval($_POST['lockernumber']) < 1) {
                        echo "Please enter a number greater than 0.";
                        exit;
                    } else {
                        $lockernumber = intval($_POST['lockernumber']); // Convert input value to an integer
                        $sql = "UPDATE lockernumber SET number='$lockernumber' WHERE id='1'";
                        if ($conn->query($sql) !== TRUE) {
                            echo "Error updating the lockernumber: " . $conn->error;
                        } else {
                            echo "Lockernumber successfully updated.";
                        }

                        // Delete existing rows from the "led" table
                        $sql_delete = "DELETE FROM led";
                        if ($conn->query($sql_delete) !== TRUE) {
                            echo "Error deleting rows from led table: " . $conn->error;
                        }

                        // Insert new rows into the "led" table
                        for ($i = 1; $i <= $lockernumber; $i++) {
                            $sql_insert = "INSERT INTO led (id, status, NeptunCode, UniPassCode) VALUES ('$i', 'off', '', '')";
                            if ($conn->query($sql_insert) !== TRUE) {
                                echo "Error creating rows in led table: " . $conn->error;
                                break;
                            }
                        }
                    }
                    successMsg("Sikeres frissítés!","Létrehoztál $lockernumber szekrényt.");
                    echo '<script>
                    window.setTimeout(function(){
                        window.location.href = "../index.php";
                    }, 2000);
                    </script>';
                    exit;
                }
            }
            $conn->close();
            ?>
        </div>
    <?php
    } else {
        echo '<button class="button"><a href="../../index.html">Lépj be!</a></button>';
    }
    ?>
</body>

</html>