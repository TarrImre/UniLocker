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

	<link type="text/css" rel="stylesheet" href="css/login.css" />

	<title>UniLocker | Regisztráció</title>

	<!-- Register service worker -->
<!--<script src="js/register-worker.js"></script>-->
	<!-- Load openui5 libraries -->
	<!--<script src="https://openui5.hana.ondemand.com/resources/sap-ui-core.js" id="sap-ui-bootstrap" data-sap-ui-libs="sap.m" data-sap-ui-preload="async" data-sap-ui-theme="sap_belize_plus"></script>-->
	<!-- Load and run the application -->
	<!--<script src="js/todo-app.js"></script>-->
	<style>
		.page1 {
			display: block;
		}

		.registerButton,
		.page2,
		.page3 {
			display: none;
		}
	</style>
</head>

<body id="body">
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
	<div class="screen-1">
			<div class="middle">
                <h1>Regisztráció</h1>
            </div>
		<form action="login.php" method="POST">
			<div class="page1">
				<div class="email" style="margin-bottom:15px;">
					<input type="text" name="VName" autocomplete="off" placeholder="Vezetéknév" autocomplete="off">
				</div>
				<div class="email" style="margin-bottom:15px;margin-top:15px;">
					<input type="text" name="KName" autocomplete="off" placeholder="Keresztnév" autocomplete="off">
				</div>
				<div class="email" style="margin-bottom:15px;margin-top:15px;">
					<input type="text" name="Email" autocomplete="off" placeholder="Email" autocomplete="off">
				</div>
			</div>

			<div class="page2">
				<div class="email" style="margin-bottom:15px;margin-top:15px;">
					<input type="password" name="Password" autocomplete="off" placeholder="Jelszó" autocomplete="off">
				</div>
				<div class="email" style="margin-bottom:15px;margin-top:15px;">
					<input type="password" name="PasswordAgain" autocomplete="off" placeholder="Jelszó ismét" autocomplete="off">
				</div>
				<div class="email" style="margin-bottom:15px;margin-top:15px;">
					<input type="text" name="NeptunCode" autocomplete="off" placeholder="Neptun kód" autocomplete="off">
				</div>
			</div>

			<div class="page3">
				<div class="email" style="margin-bottom:15px;margin-top:15px;">
					<input type="text" id="Unipass" name="UniPassCode" autocomplete="off" placeholder="Unipass kártya" readonly>
					<p id="scanButton">Beolvasás</p>
				</div>
			</div>
			<div class="middle">
				<p id="page1Button">Page 1</p>
				<p id="page2Button">Page 2</p>
				<p id="page3Button">Page 3</p>
			</div>
			<button class="login registerButton" name="submit" id="registerButton">Regisztráció</button>
			<div class="footer">
				<p>Van már felhasználód?<br><a href="index.html" style="color:#212121;"><b>Belépek!</b></a></p>
			</div>
			
		</form>
	</div>

	<?php
	if (isset($_POST['submit'])) {
		echo $msg;
	}
	?>
	<div class="left-circle"></div>

	<script src="js/nfc.js"></script>
	<!--write a javascript to change the page-->
	<script>
		var page1 = document.getElementById("page1Button");
		var page2 = document.getElementById("page2Button");
		var page3 = document.getElementById("page3Button");

		var next1 = document.getElementsByClassName("page1")[0];
		var next2 = document.getElementsByClassName("page2")[0];
		var next3 = document.getElementsByClassName("page3")[0];

		const registerButton = document.getElementById("registerButton");

		page1.onclick = function() {
			next1.style.display = "block";
			next2.style.display = "none";
			next3.style.display = "none";
			registerButton.style.display = "none";
			//registerButton.disabled = true;
		}
		page2.onclick = function() {
			next1.style.display = "none";
			next2.style.display = "block";
			next3.style.display = "none";
			registerButton.style.display = "none";
		}
		page3.onclick = function() {
			next1.style.display = "none";
			next2.style.display = "none";
			next3.style.display = "block";
			registerButton.style.display = "block";
		}
	</script>
</body>

</html>