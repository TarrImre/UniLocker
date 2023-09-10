<!DOCTYPE HTML>
<html>

<head>
	<meta charset="UTF-8">
	<link rel="icon" type="image/x-icon" href="favicon.ico">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link type="text/css" rel="stylesheet" href="scss/css/login.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
	<title>UniLocker | Regisztráció</title>
</head>

<body>
	<?php
	include('connection.php');
	include('profile/sweetalert.php');
	include('input_validation.php');

	if (isset($_POST['submit'])) {
		//mysql_real_escape_string()
		$VName = htmlspecialchars(mb_convert_case(trim($_POST['VName']), MB_CASE_TITLE, 'UTF-8'));
		$KName = htmlspecialchars(mb_convert_case(trim($_POST['KName']), MB_CASE_TITLE, 'UTF-8'));
		$Email = htmlspecialchars(trim(strtolower($_POST['Email'])));
		$Password = trim($_POST['Password']);
		$PasswordAgain = trim($_POST['PasswordAgain']);
		$NeptunCode = htmlspecialchars(trim(strtoupper($_POST['NeptunCode'])));
		$UniPassCode = htmlspecialchars(trim($_POST['UniPassCode']));
		date_default_timezone_set('Europe/Budapest');		
		$CreatedAT = date("Y-m-d H:i:s");
		
		$rank = "Student";
		$msg = "";



		$VName = mysqli_real_escape_string($conn, $VName);
		$KName = mysqli_real_escape_string($conn, $KName);
		$Email = mysqli_real_escape_string($conn, $Email);
		$Password = mysqli_real_escape_string($conn, $Password);
		$PasswordAgain = mysqli_real_escape_string($conn, $PasswordAgain);
		$NeptunCode = mysqli_real_escape_string($conn, $NeptunCode);
		$UniPassCode = mysqli_real_escape_string($conn, $UniPassCode);

		$vNameError = validateVName($VName);
		$kNameError = validateKName($KName);
		$emailError = validateEmail($Email);
		$passwordError = validatePassword($Password);
		$passwordAgainError = validatePasswordAgain($Password, $PasswordAgain);
		$neptunCodeError = validateNeptunCode($NeptunCode);
		$uniPassCodeError = validateUniPassCode($UniPassCode);

		$emailExistError = validateEmailExist($conn, $Email);
		$neptunCodeExistError = validateNeptunCodeExist($conn, $NeptunCode);
		$unipasscodeExistError = validateUniPassCodeExist($conn, $UniPassCode);

		$filleveryfield = filleveryfield($VName, $KName, $Email, $Password, $PasswordAgain, $NeptunCode, $UniPassCode);

		if($filleveryfield){
			errorMsg("Üres mező!", $filleveryfield);
		} else if ($vNameError) {
			errorMsg("Hibás Vezetéknév!", $vNameError);
		} else if ($kNameError) {
			errorMsg("Hibás Keresztnév!", $kNameError);
		} else if ($emailError) {
			errorMsg("Hibás Email!", $emailError);
		} else if ($passwordError) {
			errorMsg("Hibás Jelszó!", $passwordError);
		} else if ($passwordAgainError) {
			errorMsg("Hibás Jelszó!", $passwordAgainError);
		} else if ($neptunCodeError) {
			errorMsg("Hibás Neptun kód!", $neptunCodeError);
		} else if ($uniPassCodeError) {
			errorMsg("Hibás Unipass kártya!", $uniPassCodeError);
		} else if ($emailExistError) {
			errorMsg("Hibás Email!", $emailExistError);
		} else if ($neptunCodeExistError) {
			errorMsg("Hibás Neptun kód!", $neptunCodeExistError);
		} else if ($unipasscodeExistError) {
			errorMsg("Hibás Unipass kártya!", $unipasscodeExistError);
		} else{
			$passwordencrypt = password_hash($Password, PASSWORD_DEFAULT);
			$push = "INSERT INTO users (VName, KName, Email, Password, NeptunCode, UniPassCode, CreatedAT, Rank) VALUES ('$VName', '$KName', '$Email', '$passwordencrypt', '$NeptunCode', '$UniPassCode','$CreatedAT', '$rank')";
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
					<div class="input_style" style="margin-bottom:15px;">
						<input type="text" name="VName" placeholder="Vezetéknév" autocomplete="off" value="<?php echo isset($_POST['VName']) ? $_POST['VName'] : "";  ?>">
					</div>
					<div class="input_style" style="margin-bottom:15px;margin-top:15px;">
						<input type="text" name="KName" placeholder="Keresztnév" autocomplete="off" value="<?php echo isset($_POST['KName']) ? $_POST['KName'] : ""; ?>">
					</div>
					<div class="input_style" style="margin-bottom:15px;margin-top:15px;">
						<input type="text" name="Email" placeholder="Email" autocomplete="off" value="<?php echo isset($_POST['Email']) ? $_POST['Email'] : ""; ?>">
					</div>
				</div>

				<div class="page2">
					<div class="input_style" style="margin-bottom:15px;">
						<input type="password" name="Password" placeholder="Jelszó" autocomplete="off">
					</div>
					<div class="input_style" style="margin-bottom:15px;margin-top:15px;">
						<input type="password" name="PasswordAgain" placeholder="Jelszó ismét" autocomplete="off">
					</div>
					<div class="input_style" style="margin-bottom:15px;margin-top:15px;">
						<input type="text" name="NeptunCode" placeholder="Neptun kód" autocomplete="off" value="<?php echo isset($_POST['NeptunCode']) ? $_POST['NeptunCode'] : ""; ?>">
					</div>
				</div>

				<div class="page3">
					<div class="unipassHide">
						<div class="input_style" style="background-color: #99c2ff;color: #0056b3; padding-top:0px">
							<p>Lehetőség van regisztrálni az UniPass kártyádat, így a kártyával is kutdod nyitni a szekrényed.<br>Szeretnéd?</p>
							<p id="unipassConfirm" class="cursor" style="margin:0px"><b>Igen >></b></p>
						</div>
					</div>
					<div class="unipassnotAvailable">
						<div class="input_style" style="background-color: #ff9999;color: #8f0000; padding-top:0px">
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
						<input type="text" id="Unipass" name="UniPassCode" autocomplete="off" placeholder="Unipass kártya" style="display: none;" value="ERROR" readonly>
					</div>
				</div>
				<div class="middle">
					<p id="page1Button" class="pageDotIcon">1</p>
					<p id="page2Button" class="pageDotIcon">2</p>
					<p id="page3Button" class="pageDotIcon">3</p>
				</div>
				<button class="login registerButton" name="submit" id="registerButton">Regisztráció</button>
				<div class="footer" style="margin-bottom:0;padding-bottom: 0">
					<p>Van már felhasználód?<br><a href="index.php" style="color:#212121;"><b>Belépek!</b></a></p>
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