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

	<link type="text/css" rel="stylesheet" href="scss/css/login.css" />

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

	<title>UniLocker | Regisztráció</title>

	<!-- Register service worker -->
	<!--<script src="js/register-worker.js"></script>-->
	<!-- Load openui5 libraries -->
	<!--<script src="https://openui5.hana.ondemand.com/resources/sap-ui-core.js" id="sap-ui-bootstrap" data-sap-ui-libs="sap.m" data-sap-ui-preload="async" data-sap-ui-theme="sap_belize_plus"></script>-->
	<!-- Load and run the application -->
	<!--<script src="js/todo-app.js"></script>-->
	<style>
		* {
			-webkit-tap-highlight-color: transparent;
		}

		.pageDotIcon {
			width: 20px;
			height: 20px;
			border-radius: 50%;
			background-color: #3498db;
			margin-right: 5px;
			cursor: pointer;
			color: white;
			text-align: center;
			padding: 5px;
			/*centered the content*/
			display: flex;
			align-items: center;
			/* Középre igazítás függőlegesen */
			justify-content: center;
			/* Középre igazítás vízszintesen */



		}

		.activeDot {
			background-color: #1dbef9;
		}

		.page1 {
			display: block;
		}

		.registerButton,
		.page2,
		.page3,
		.unipassShow,
		.unipassnotAvailable {
			display: none;
		}

		.registerButton {
			margin-left: auto;
			margin-right: auto;
		}

		.cursor {
			cursor: pointer;
		}
	</style>
	<style>
		.circle-container {
			text-align: center;
			display: flex;
			align-items: center;
			/* Középre igazítás függőlegesen */
			justify-content: center;
			/* Középre igazítás vízszintesen */
			margin-top: 60px;
			margin-bottom: 70px;
		}

		.circle {
			width: 10px;
			height: 10px;
			border-radius: 50%;
			background: #1dbef9;
			position: absolute;
			/* Abszolút pozíció a konténerhez viszonyítva */
			animation: mymove 6s infinite;
			animation-delay: calc(var(--delay) * -1s);
			/* Késleltetés beállítása */
		}

		.green-circle {
			background: #44cc66;
			width: 200px;
			height: 200px;
			border-radius: 50%;
			opacity: 0.5;
		}

		.circle:nth-child(1) {
			--delay: 0;
			/* 0 másodperc késleltetés az első körhöz */
		}

		.circle:nth-child(2) {
			--delay: 1;
			/* 2 másodperc késleltetés a második körhöz */
		}

		.circle:nth-child(3) {
			--delay: 2;
			/* 4 másodperc késleltetés a harmadik körhöz */
		}

		@keyframes mymove {
			0% {
				opacity: 1;
			}

			100% {
				width: 250px;
				height: 250px;
				opacity: 0;
			}
		}

		.arrows {
			cursor: pointer;
		}
	</style>
</head>

<body id="body">
	<?php
	include('connection.php');
	include('profile/sweetalert.php');
	if (isset($_POST['submit'])) {

		$VName = mb_convert_case(trim($_POST['VName']), MB_CASE_TITLE, 'UTF-8');
		$KName = mb_convert_case(trim($_POST['KName']), MB_CASE_TITLE, 'UTF-8');
		$Email = trim(strtolower($_POST['Email']));
		$Password = trim($_POST['Password']);
		$PasswordAgain = trim($_POST['PasswordAgain']);
		$NeptunCode = trim(strtoupper($_POST['NeptunCode']));
		$UniPassCode = trim($_POST['UniPassCode']);
		date_default_timezone_set('Europe/Budapest');
		$CreatedAT = date("Y-m-d h:i:sa");
		$rank = "Student";
		$msg = "";

		//check the neptuncode and the email and the unipass code already exist, if the unipass code is EMPTY dont count it

		$check = "SELECT * FROM users WHERE NeptunCode = '$NeptunCode'";
		$check2 = "SELECT * FROM users WHERE Email = '$Email'";
		$check3 = "SELECT * FROM users WHERE UniPassCode = '$UniPassCode'";


		$result = mysqli_query($conn, $check);
		$result2 = mysqli_query($conn, $check2);
		$result3 = mysqli_query($conn, $check3);
		$count = mysqli_num_rows($result);
		$count2 = mysqli_num_rows($result2);

		if ($UniPassCode == "EMPTY") {
			$count3 = 0;
		} else {
			$count3 = mysqli_num_rows($result3);
		}

		if (empty($VName) || empty($KName) || empty($Email) || empty($Password) || empty($PasswordAgain) || empty($NeptunCode)) {
			errorMsg("Minden mező kötelező!", "");
		} else if ($Password != $PasswordAgain) {
			errorMsg("Hibás jelszó!", "A két jelszó nem egyezik meg!");
		} else if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
			errorMsg("Hibás Email!", "Rossz email formátum!");
		} else if (strlen($Password) < 8) {
			errorMsg("Hibás jelszó!", "A jelszónak legalább 8 karakter hosszúnak kell lennie!");
		} else if (strlen($NeptunCode) != 6) {
			errorMsg("Hibás Neptun kód!", "A Neptun kód 6 karakter hosszú!");
		}
		//make a pregmatch to the kname and vname, and control the hungarian characters and let the space
		else if (!preg_match("/^[a-zA-ZáéíóöőúüűÁÉÍÓÖŐÚÜŰ ]*$/", $VName)) {
			errorMsg("Hibás Vezetéknév!", "A vezetéknév csak betűket tartalmazhat!");
		} else if (!preg_match("/^[a-zA-ZáéíóöőúüűÁÉÍÓÖŐÚÜŰ ]*$/", $KName)) {
			errorMsg("Hibás Keresztnév!", "A keresztnév csak betűket tartalmazhat!");
		} else if (!preg_match("/^[a-zA-Z0-9]*$/", $NeptunCode)) {
			errorMsg("Hibás Neptun kód!", "A Neptun kód csak betűket és számokat tartalmazhat!");
		} else if ($count > 0) {
			errorMsg("Hibás Neptun kód!", "A Neptun kód már létezik!");
		} else if ($count2 > 0) {
			errorMsg("Hibás Email!", "Az email cím már létezik!");
		} else if ($count3 > 0) {
			errorMsg("Hibás Unipass kártya!", "Az Unipass kártyát már regisztrálták!");
		} else {
			$password_md5 = $Password;

			$push = "INSERT INTO users (VName, KName, Email, Password, NeptunCode, UniPassCode, CreatedAT, Rank) VALUES ('$VName', '$KName', '$Email', '$password_md5', '$NeptunCode', '$UniPassCode','$CreatedAT', '$rank')";
			if (mysqli_query($conn, $push)) {
				successMsg("Sikeres regisztráció!", "Mostmár beléphetsz!");
				header("Refresh: 2; url=index.php");
				exit;
			}
		}
	}
	$conn->close();
	?>
	<div class="container">
		<div class="screen-1">
			<div style="text-align:center;margin-bottom:10px;">
				<h1>Regisztráció</h1>
			</div>
			<form method="POST">
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
					<div class="email" style="margin-bottom:15px;">
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
					<div class="unipassHide">
						<div class="email" style="background-color: #99c2ff;color: #0056b3; padding-top:0px">
							<p>Lehetőség van regisztrálni az UniPass kártyádat, így a kártyával is kutdod nyitni a szekrényed.<br>Szeretnéd?</p>
							<p id="unipassConfirm" class="cursor" style="margin:0px"><b>Igen >></b></p>
						</div>
					</div>
					<div class="unipassnotAvailable">
						<div class="email" style="background-color: #ff9999;color: #8f0000; padding-top:0px">
							<p>Sajnáljuk, az UniPass regisztráció nem elérhető!</p>
						</div>
					</div>
					<div class="unipassShow">
						<div class="circle-container">
							<div class="circle"></div>
							<div class="circle"></div>
							<div class="circle"></div>
						</div>
						<div class="middle" style="margin-bottom:0px;padding-bottom:0px">
							<p id="scanButton" style="text-align:center;padding:5px;font-size:25px;margin-top:45px;color:white;background-color:#1dbef9;width:35px;height:35px;border-radius:50%;"><i class='bx bxs-hand-up'></i></p>
						</div>
						<p id="randomtext" style="text-align:center;color:#888888;margin-top:0px;padding-top:0px;">Tartsd a kártyát a telefon hátuljához.</p>
						<input type="text" id="Unipass" name="UniPassCode" autocomplete="off" placeholder="Unipass kártya" style="display: none;" value="EMPTY" readonly>
					</div>
				</div>
				<div class="middle">
					<p id="page1Button" class="pageDotIcon">1</p>
					<p id="page2Button" class="pageDotIcon">2</p>
					<p id="page3Button" class="pageDotIcon">3</p>
				</div>
				<button class="login registerButton" name="submit" id="registerButton">Regisztráció</button>
				<div class="footer" style="margin-bottom:0;padding-bottom: 0">
					<p>Van már felhasználód?<br><a href="index.html" style="color:#212121;"><b>Belépek!</b></a></p>
				</div>
			</form>
		</div>
	</div>
	<div class="left-circle"></div>

	<script src="js/nfc.js"></script>
	<!--write a javascript to change the page-->
	<script>
		const page1 = document.getElementById("page1Button");
		const page2 = document.getElementById("page2Button");
		const page3 = document.getElementById("page3Button");
		const unipassConfirm = document.getElementById("unipassConfirm");
		const unipassShow = document.querySelector(".unipassShow");
		const unipassHide = document.querySelector(".unipassHide");
		const unipassnotAvailable = document.querySelector(".unipassnotAvailable");

		const next1 = document.querySelector(".page1");
		const next2 = document.querySelector(".page2");
		const next3 = document.querySelector(".page3");

		const registerButton = document.getElementById("registerButton");
		setActivePage(page1);

		const isMobile = navigator.userAgentData.mobile;
		if (!isMobile) {
			unipassHide.style.display = "none";
			unipassnotAvailable.style.display = "block";
		} else {
			unipassHide.style.display = "block";
			unipassnotAvailable.style.display = "none";
		}

		page1.onclick = function() {
			showPage(next1);
			hidePage(next2);
			hidePage(next3);
			hideElement(registerButton);
			setActivePage(page1);
		}

		page2.onclick = function() {
			hidePage(next1);
			showPage(next2);
			hidePage(next3);
			hideElement(registerButton);
			setActivePage(page2);
		}

		page3.onclick = function() {
			hidePage(next1);
			hidePage(next2);
			showPage(next3);
			showElement(registerButton);
			setActivePage(page3);
		}

		unipassConfirm.onclick = function() {
			showElement(unipassShow);
			hideElement(unipassHide);
		}

		function showPage(page) {
			page.style.display = "block";
		}

		function hidePage(page) {
			page.style.display = "none";
		}

		function showElement(element) {
			element.style.display = "block";
		}

		function hideElement(element) {
			element.style.display = "none";
		}

		function setActivePage(activePage) {
			page1.classList.remove("activeDot");
			page2.classList.remove("activeDot");
			page3.classList.remove("activeDot");
			activePage.classList.add("activeDot");
		}
	</script>
</body>

</html>