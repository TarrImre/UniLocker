<!DOCTYPE HTML>
<html>

<head>
	<meta charset="UTF-8">
	<link rel="icon" type="image/x-icon" href="favicon.ico">
	<!-- PWA related meta tags -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- PWA manifest -->
	<link rel="manifest" href="js/manifest.json">
	<!-- Add to home screen for Safari on iOS -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-title" content="UniPost">
	<link rel="apple-touch-icon" href="icons/icon-152x152_V2.png">
	<!-- Windows related -->
	<meta name="msapplication-TileImage" content="icons/icon-144x144_V2.png">
	<meta name="msapplication-TileColor" content="#1b6de1">
	<meta name="theme-color" content="#1b6de1">

	<link type="text/css" rel="stylesheet" href="css/style.css" />

	<title>UniPost</title>

	<!-- Register service worker -->
	<script src="js/register-worker.js"></script>
	<!-- Load openui5 libraries -->
	<script src="https://openui5.hana.ondemand.com/resources/sap-ui-core.js" id="sap-ui-bootstrap" data-sap-ui-libs="sap.m" data-sap-ui-preload="async" data-sap-ui-theme="sap_belize_plus"></script>
	<!-- Load and run the application -->
	<script src="js/todo-app.js"></script>
</head>

<body id="body" class="sapUiBody">
	<?php
	include('connection.php');
	if (isset($_POST['submit'])) {

		$VName = $_POST['VName'];
		$KName = $_POST['KName'];
		$Email = $_POST['Email'];
		$Password = $_POST['Password'];
		$PasswordAgain = $_POST['PasswordAgain'];
		$NeptunCode = strtoupper($_POST['NeptunCode']);
		$UniPassCode = $_POST['UniPassCode'];
		date_default_timezone_set('Europe/Budapest');
		$CreatedAT = date("Y-m-d h:i:sa");

		$msg = "";
		if (empty($VName) || empty($KName) || empty($Email) || empty($Password) || empty($PasswordAgain) || empty($NeptunCode) || empty($UniPassCode)) {
			$msg = "Minden mező kötelező!";
		} else if ($Password != $PasswordAgain) {
			$msg = "A két jelszó nem egyezik meg!";
		} else {
			$password_md5 = $Password;
			$push = "INSERT INTO users (VName, KName, Email, Password, NeptunCode, UniPassCode, CreatedAT) VALUES ('$VName', '$KName', '$Email', '$password_md5', '$NeptunCode', '$UniPassCode','$CreatedAT')";
			if (mysqli_query($conn, $push)) {
				$msg = '<p>Sikeres, <a href="login.php" style="color:#212121;"><b>Belépek!</b></a></p>';
			}
		}
	}

	?>
	<div class="hatter">
		<div class="container">
			<form action="" method="POST">
				<div class="mb-3">
					<label for="formGroupExampleInput" class="form-label">Vezetéknév</label>
					<input type="text" class="form-control" name="VName" autocomplete="off">
				</div>
				<div class="mb-3">
					<label for="formGroupExampleInput2" class="form-label">Keresztnév</label>
					<input type="text" class="form-control" name="KName" autocomplete="off">
				</div>
				<div class="mb-3">
					<label for="formGroupExampleInput2" class="form-label">Email</label>
					<input type="text" class="form-control" name="Email" autocomplete="off">
				</div>
				<div class="mb-3">
					<label for="formGroupExampleInput2" class="form-label">Jelszó</label>
					<input type="password" class="form-control" name="Password" autocomplete="off">
				</div>
				<div class="mb-3">
					<label for="formGroupExampleInput2" class="form-label">Jelszó ismét</label>
					<input type="password" class="form-control" name="PasswordAgain" autocomplete="off">
				</div>
				<div class="mb-3">
					<label for="formGroupExampleInput2" class="form-label">Neptun kód</label>
					<input type="text" class="form-control" name="NeptunCode" autocomplete="off">
				</div>
				<div class="mb-3">
					<label for="formGroupExampleInput2" class="form-label">Unipass kártya</label>
					<input type="text" class="form-control" id="Unipass" name="UniPassCode" readonly>
					<p class="btn btn-secondary mt-3" id="scanButton">Beolvasás</p>
				</div>
				<button class="button" name="submit">Regisztráció</button>
			</form>
			<?php
			if (isset($_POST['submit'])) {
				echo $msg;
			}
			?>
			<p><a href="index.html" style="color:#212121;"><b>Kezdőlap</b></a></p>
			<p>Van már felhasználód?<br><a href="index.php" style="color:#212121;"><b>Belépek!</b></a></p>
		</div>
	</div>


	<script src="js/nfc.js"></script>
</body>

</html>