<?php
function takenLocker(){
  sqlQuery("SELECT * FROM lockers WHERE NeptunCode !=''");
}

function registeredUser(){
  sqlQuery("SELECT * FROM users");
}

function registeredWithUniPass(){
  sqlQuery("SELECT * FROM users WHERE UniPassCode !=''");
}

function sqlQuery($sqlCommand){
  include('../../connection.php');
  $result = $conn->query($sqlCommand);
  $number = 0;
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $number++;
    }
  }
  echo $number;
} 
?>