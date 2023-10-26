<?php
$host="localhost";
$hostname="";
$hostpw="";
$dbname="";
$conn=mysqli_connect($host,$hostname,$hostpw,$dbname);
$conn->set_charset("utf8");
if(!$conn){
    echo "Hiba ".mysqli_connect_error();
}
?>