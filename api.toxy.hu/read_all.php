<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Initialize an empty array to hold the objects
$response = array();

// Include database connect class
$filepath = realpath(dirname(__FILE__));
require_once($filepath . "/db_connect.php");

// Kapcsolódunk az adatbázishoz
$db = new DB_CONNECT();
$link = $db->link;

// Ellenőrizzük, hogy az "id" paraméter át lett-e adva
if (isset($_GET["id"])) {
    $id = intval($_GET["id"]);
} else {
    $id = 1; // Alapértelmezett érték, ha az "id" nincs megadva
}

// Lekérdezzük az API kulcsot az adatbázisból
$result = mysqli_query($link, "SELECT * FROM settings WHERE settingsName = 'ApiKey'") or die(mysqli_error($link));

if (!empty($result) && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $db_api_key = $row["value"];

    // Ellenőrizzük az "apikey" paramétert
    if (isset($_GET["apikey"])) {
        $api_key = $_GET["apikey"];

        // Ha az API kulcs helyes, akkor folytatjuk
        if ($api_key === $db_api_key) {
            // Lekérdezés a megfelelő locker-ek kiválasztásához
            $startId = ($id - 1) * 16 + 1;
            $endId = $startId + 15;

            // Módosított lekérdezés a kívánt locker-ek kiválasztásához
            $result = mysqli_query($link, "SELECT * FROM lockers WHERE id >= $startId AND id <= $endId") or die(mysqli_error($link));
            $ledArray = array();
            if (!empty($result) && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    $ledObject = array(
                        "id" => $row["id"],
                        "status" => $row["status"],
                        "NeptunCode" => $row["NeptunCode"],
                        "UniPassCode" => $row["UniPassCode"]
                    );

                    $ledArray[$row["id"]] = array($ledObject);
                }

                // success
                $response["success"] = 1;
                $response["led"] = $ledArray;

                // Echoing JSON response
                echo json_encode($response, JSON_PRETTY_PRINT);
            } else {
                // No locker data found
                $response["success"] = 0;
                $response["message"] = "Nincs talalat.";

                // Echo JSON response
                echo json_encode($response, JSON_PRETTY_PRINT);
            }
        } else {
            // Invalid API key
            $response["success"] = 0;
            $response["message"] = "Hibas API kulcs.";

            // Echo error JSON
            echo json_encode($response, JSON_PRETTY_PRINT);
        }
    } else {
        // Missing API key
        $response["success"] = 0;
        $response["message"] = "Hianyzo API kulcs.";

        // Echo error JSON
        echo json_encode($response, JSON_PRETTY_PRINT);
    }
} else {
    // No API key found in database
    $response["success"] = 0;
    $response["message"] = "Az API kulcs nem talalhato az adatbazisban.";

    // Echo error JSON
    echo json_encode($response, JSON_PRETTY_PRINT);
}
?>
