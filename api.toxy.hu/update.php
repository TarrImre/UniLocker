<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Creating Array for JSON response
$response = array();

// Include data base connect class
$filepath = realpath(dirname(__FILE__));
require_once($filepath . "/db_connect.php");

// Check for API key
if (isset($_GET['id']) && isset($_GET['status']) && isset($_GET['NeptunCode']) && isset($_GET['UniPassCode']) && isset($_GET['apikey'])) {
    $api_key = $_GET['apikey'];

    // Connecting to database
    $db = new DB_CONNECT();
    $link = $db->link;

    $result = mysqli_query($link, "SELECT * FROM settings WHERE settingsName = 'ApiKey'") or die(mysqli_error($link));
    $db_api_key = "";
    if (!empty($result)) {
        if (mysqli_num_rows($result) > 0) {
            $result = mysqli_fetch_array($result);
            $db_api_key = $result["value"];
        }
    }

    // Check if the API key is correct
    if ($api_key === $db_api_key) {
        $id = $_GET['id'];
        $status = $_GET['status'];
        $NeptunCode = $_GET['NeptunCode'];
        $UniPassCode = $_GET['UniPassCode'];


        // Fire SQL query to update LED status data by id
        $result = mysqli_query($link, "UPDATE lockers SET status='$status',NeptunCode='$NeptunCode',UniPassCode='$UniPassCode' WHERE id = '$id'");

        // Check for successful execution of query
        if ($result) {
            // successfully updation of LED status (status)
            $response["success"] = 1;
            $response["message"] = "Sikeres frissites!";

            // Show JSON response
            echo json_encode($response);
        } else {
            // Query execution failed
            $response["success"] = 0;
            $response["message"] = "Sikertelen frissites!";

            // Show JSON response
            echo json_encode($response);
        }
    } else {
        // Invalid API key
        $response["success"] = 0;
        $response["message"] = "Hibas API kulcs!";

        // Show JSON response
        echo json_encode($response);
    }
} else {
    // Missing parameters
    $response["success"] = 0;
    $response["message"] = "Hianyzo parameter(ek). Probald ujra!";

    // Show JSON response
    echo json_encode($response);
}
