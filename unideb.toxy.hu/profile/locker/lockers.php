<?php
include('../../connection.php');

echo '<section class="buttons-wrapper">';
$lockersNumber = 0;
//query the lockers number from the database
$sql = "SELECT value FROM settings WHERE settingsName='NumberOfLockers'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {

    $lockersNumber = $row["value"];
  }
}

for ($i = 1; $i <= $lockersNumber; $i++) {
  $sql = "SELECT id, NeptunCode FROM lockers WHERE id = '$i'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      if ($row["NeptunCode"] != "") {
        $foglaltMsg = "Foglalt";
      } else {
        $foglaltMsg = "Üres";
      }
    }
  }

  echo '<div id="dynamicDiv"';
  if ($foglaltMsg === "Foglalt") {
    echo ' style="display: none;"';
  }
  /*if ($foglaltMsg == "Foglalt") {
  echo 'style="background-color:#FFA5A3;"';
  } else {
    echo 'style="background-color:#A5E5A3;"';
  }*/
  echo '>';
  echo '<label class="toggler-wrapper">';
  echo "<input type='checkbox' class='save-cb-state iconChange' name='$i' value='$i' onclick='showSendButton()'>";
  echo '<div class="toggler-slider">';
  echo  '<p style="color:white;font-size:1rem;"><b>' . $i . '.' . /*. $foglaltMsg .*/ '</b></p>';
  echo '</div>';
  echo '</label>';
  echo '</div>';
}

echo '</section>';



?>