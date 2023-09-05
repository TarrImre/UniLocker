<?php

function getApiKey()
{
    include('../../connection.php');
    $result = mysqli_query($conn, "SELECT * FROM settings WHERE settingsName = 'ApiKey'") or die(mysqli_error($conn));
    $db_api_key = "";
    if (!empty($result) && mysqli_num_rows($result) > 0) {
        $result = mysqli_fetch_array($result);
        $db_api_key = $result["value"];
    }
    return $db_api_key;
}
