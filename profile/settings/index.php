<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
 
<?php
session_start();
if (isset($_SESSION['neptuncode'])) {
  include('../../connection.php');
  include('../header.php');

?>
</head>
<body>

<?php include('../nav.php'); ?>

  <div class="middle">
  <h1 style="font-family: Helvetica; color: black">Profil beállítások</h1>

  <div class="center">
    <div class="form">
      
      <form action="update.php" method="POST">
        <div class="form-group">
          <?php
          $sql = "SELECT * FROM users WHERE id='$_SESSION[id]'";
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_array($result);
          if (is_array($row)) {
            $id = $row['id'];
            $vname = $row['VName'];
            $kname = $row['KName'];
            $email = $row['Email'];
            $password = $row['Password'];
            $neptuncode = $row['NeptunCode'];
            $unipasscode = $row['UniPassCode'];
          }
          ?>
          <label for="name">Vezetéknév</label>
          <input type="text" name="VName" value="<?php echo $vname ?>">
          <br>
          <label for="name">Keresztnév</label>
          <input type="text" name="KName" value="<?php echo $kname ?>">
          <br>
          <label for="name">Email</label>
          <input type="text" name="Email" value="<?php echo $email ?>">
          <br>
          <label for="name">Jelszó</label>
          <input type="text" name="Password" value="<?php echo $password ?>">
          <br>
          <label for="name">Neptun kód</label>
          <input type="text" name="NeptunCode" value="<?php echo $neptuncode ?>">
          <br>
          <div class="mb-3">
            <label for="formGroupExampleInput2" class="form-label">Unipass kártya</label>
            <input type="text" class="form-control" id="Unipass" name="UniPassCode" value="<?php echo $unipasscode ?>" readonly>
          </div>
          <input type="hidden" name="post_data" value="1">
          <button type="submit" name="update">Frissítés</button>
          <button type="submit" name="delete">Fiók törlése</button>

        </div>
      </form>
    </div>
  </div>
  </div>

  <script src="../../js/nfc.js"></script>


<?php
} else echo '<button class="button"><a href="../../index.html">Lépj be!</a></button>';
?>
</body>
</html>