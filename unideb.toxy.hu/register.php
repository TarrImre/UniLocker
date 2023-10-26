<!DOCTYPE HTML>
<html>

<head>
	<meta charset="UTF-8">
	<link rel="icon" type="image/x-icon" href="pwa/icons/favicon.ico">
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

		if ($filleveryfield) {
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
		} else {
			$passwordencrypt = password_hash($Password, PASSWORD_DEFAULT);
			$push = "INSERT INTO users (VName, KName, Email, Password, NeptunCode, UniPassCode, CreatedAT, Rank) VALUES ('$VName', '$KName', '$Email', '$passwordencrypt', '$NeptunCode', '$UniPassCode','$CreatedAT', '$rank')";
			if (mysqli_query($conn, $push)) {
				successMsg("Sikeres regisztráció!", "Mostmár beléphetsz!");
				header("Refresh: 2; url=index.php");
				//exit;
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
				<div class="page1" id="page1">
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

				<div class="page2" id="page2">
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

				<div class="page3" id="page3">
					<div class="unipassHide">
						<div class="input_style" style="background-color: #99c2ff;color: #0056b3; padding-top:0px">
							<p>Lehetőség van regisztrálni az UniPass kártyádat, így a kártyával is kitudod nyitni a szekrényed.<br>Szeretnéd?</p>
							<p id="unipassConfirm" class="cursor" style="margin:0px"><b>Igen >></b></p>
						</div>
					</div>
					<div class="unipassnotAvailable">
						<div class="input_style" style="background-color: #ff9999;color: #8f0000; padding-top:0px">
							<p>Sajnáljuk, az UniPass regisztráció nem elérhető!</p>
						</div>
					</div>
					<div class="unipassShow">
						<div class="circle-container" style="margin-top:20px;">
							<div class="circle"></div>
							<div class="circle"></div>
							<div class="circle"></div>
							<p id="scanButton" style="z-index:1;text-align:center;padding:5px;font-size:25px;color:white;"><i class='bx bxs-hand-up'></i></p>
						</div>
						<!--<div class="middle" style="margin-bottom:0px;padding-bottom:0px">
							<p id="scanButton" style="text-align:center;padding:5px;font-size:25px;margin-top:45px;color:white;background-color:#1dbef9;width:35px;height:35px;border-radius:50%;"><i class='bx bxs-hand-up'></i></p>
						</div>-->
						<p id="randomtext" style="text-align:center;color:#888888;margin-top:80px;padding-top:0px;">Nyomd meg középen, majd tartsd a kártyát a telefon hátuljához!</p>
						<input type="text" id="Unipass" name="UniPassCode" autocomplete="off" placeholder="Unipass kártya" style="display: none;" value="ERROR" readonly>
					</div>
				</div>

				<div class="middle">
					<p id="prevPageButton" class="pageDotIcon" style="width:20px;height:20px;"><</p>
					<p id="nextPageButton" class="pageDotIcon" style="width:20px;height:20px;">></p>
				</div>
				<button class="login registerButton" name="submit" id="registerButton">Regisztráció</button>
				<div class="footer" style="margin-bottom:0;padding-bottom: 0">
					<p style="font-size:13px;">Van már felhasználód?<br><a href="index.php" style="color:#212121;"><b>Belépek!</b></a></p>
				</div>
			</form>
		</div>
	</div>
	<div class="left-circle"></div>

	<script src="js/nfc.js"></script>

	<script>
		function iOS() {
			return [
					'iPad Simulator',
					'iPhone Simulator',
					'iPod Simulator',
					'iPad',
					'iPhone',
					'iPod'
				].includes(navigator.platform)
				// iPad on iOS 13 detection
				||
				(navigator.userAgent.includes("Mac") && "ontouchend" in document)
		}
		iOS() ? document.getElementById("randomtext").innerHTML = "iOS eszközön nem elérhető! Egy barátod Android eszközéről később is beállíthatod." : console.log("Android");


		const unipassConfirm = document.getElementById("unipassConfirm");
		const unipassShow = document.querySelector(".unipassShow");
		const unipassHide = document.querySelector(".unipassHide");
		const unipassnotAvailable = document.querySelector(".unipassnotAvailable");

		const page1 = document.getElementById("page1");
		const page2 = document.getElementById("page2");
		const page3 = document.getElementById("page3");

		const prevPageButton = document.getElementById("prevPageButton");
		const nextPageButton = document.getElementById("nextPageButton");

		let currentPage = 1; // Initialize with page 1

		const registerButton = document.getElementById("registerButton");

		// Function to show a specific page and update the currentPage variable
		function showPage(pageNumber) {
			hideAllPages();
			switch (pageNumber) {
				case 1:
					page1.style.display = "block";
					currentPage = 1;
					registerButton.style.display = "none";
					break;
				case 2:
					page2.style.display = "block";
					currentPage = 2;
					registerButton.style.display = "none";
					break;
				case 3:
					page3.style.display = "block";
					currentPage = 3;
					registerButton.style.display = "block";
					break;
			}
		}

		// Function to hide all pages
		function hideAllPages() {
			page1.style.display = "none";
			page2.style.display = "none";
			page3.style.display = "none";
		}

		// Event handler for the "Prev" button
		prevPageButton.addEventListener("click", () => {
			if (currentPage > 1) {
				showPage(currentPage - 1);
			}
		});

		// Event handler for the "Next" button
		nextPageButton.addEventListener("click", () => {
			if (currentPage < 3) {
				showPage(currentPage + 1);
			}
		});

		// Initially show the first page
		showPage(1);

		// Event handler for the "unipassConfirm" button
		unipassConfirm.addEventListener("click", () => {
			showElement(unipassShow);
			hideElement(unipassHide);
		});

		const isMobile = navigator.userAgentData.mobile;
		if (!isMobile) {
			unipassHide.style.display = "none";
			unipassnotAvailable.style.display = "block";
		} else {
			unipassHide.style.display = "block";
			unipassnotAvailable.style.display = "none";
		}

		// Function to show an element
		function showElement(element) {
			element.style.display = "block";
		}

		// Function to hide an element
		function hideElement(element) {
			element.style.display = "none";
		}
	</script>

</body>

</html>