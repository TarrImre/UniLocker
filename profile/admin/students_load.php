<?php

include('../../connection.php');
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<table style="background-color: #fafafa;">
  <thead>
    <tr>
      <th scope="col">Id</th>
      <th scope="col">Vezetéknév</th>
      <th scope="col">Keresztnév</th>
      <th scope="col">Email</th>
      <th scope="col">Jelszó</th>
      <th scope="col">Neptun</th>
      <th scope="col">Unipass</th>
      <th scope="col">Létrehozva</th>
      <th scope="col">Rang</th>
    </tr>
  </thead>
  <tbody>
    <?php
    if ($result->num_rows > 0) {
      while ($row  = mysqli_fetch_array($result)) {
        $id = $row['id'];
        echo  '<tr>
              <td data-label="Id">' . $id . '</td>
              <td data-label="Vezetéknév">' . $row['VName'] . '</td>
              <td data-label="Keresztnév">' . $row['KName'] . '</td>
              <td data-label="Email">' . $row['Email'] . '</td>
              <td data-label="Jelszó">' . $row['Password'] . '</td>
              <td data-label="Neptun">' . $row['NeptunCode'] . '</td>
              <td data-label="Unipass">' . $row['UniPassCode'] . '</td>
              <td data-label="Létrehozva">' . $row['CreatedAT'] . '</td>
              <td data-label="Rang">' . $row['Rank'] . '</td>
            </tr>';
      }
    } else {
      echo '<tr><td colspan="9">No data found</td></tr>';
    }
    ?>
  </tbody>
</table>