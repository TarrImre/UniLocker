<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Creating Array for JSON response
$response = array();

// Include data base connect class
$filepath = realpath(dirname(__FILE__));
require_once($filepath . "/db_connect.php");


if (isset($_GET['esp']))  {
// Connecting to database
$db = new DB_CONNECT();
$link = $db->link;

// Fetch API key from database settings
$result = mysqli_query($link, "SELECT * FROM settings WHERE settingsName = 'ApiKey'") or die(mysqli_error($link));

//make a query to count how many lockers 
$result2 = mysqli_query($link, "SELECT * FROM settings WHERE settingsName ='NumberOfLockers'") or die(mysqli_error($link));

//make a query to get the physical locker number
$result3 = mysqli_query($link, "SELECT * FROM settings WHERE settingsName ='PhysicalLockers'") or die(mysqli_error($link));

$db_api_key = "";

    if (!empty($result) && mysqli_num_rows($result) > 0) {
        $result = mysqli_fetch_array($result);
        $db_api_key = $result["value"];

        $result2 = mysqli_fetch_array($result2);
        $locker_count = $result2["value"];

        $result3 = mysqli_fetch_array($result3);
        $physical_locker = $result3["value"];

        //json the db_api_key
        $response["success"] = 1;
        $response["encrypt_api_key"] = $db_api_key;
        $response["locker_count"] = $locker_count;
		$response["physical_locker"] = $physical_locker;
        //echo json_encode($response, JSON_PRETTY_PRINT);
		 echo json_encode($response);
    }
}
else{
    $response["success"] = 0;
    $response["message"] = "Hianyzo adatok!";

    echo json_encode($response);
}

?>