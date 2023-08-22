<?php
$sql = "SELECT * FROM users WHERE id='$_SESSION[id]'";
$result = $conn->query($sql);
$row = mysqli_fetch_array($result);
if (is_array($row)) {
  $vname = $row['VName'];
  $kname = $row['KName'];
  $rank = $row['Rank'];
}
?>
<!--=============== HEADER ===============-->


<header class="header" id="header">
  <nav class="nav container">
    <a href="index.php" class="nav__logo">
      <?php $welcomeMessage = randomWelcomeMsg();
      echo $welcomeMessage; ?>
      <!--<img style='position: absolute;margin-left:6px' src='../../icons/wave.png' width='25px' height='25px'>-->
      <br>
      <?php echo "<p class='font_bold'>$vname $kname</p>" ?><?php /* echo $_SESSION['UniPassCode']; */ ?>
    </a>
    <form method="post">
      <div class="nav__menu transparent-background" id="nav-menu">
        <ul class="nav__list">
          <li class="nav__item">
            <button name="profil" class="nav__link transparent">
              <i class='bx bx-user nav__icon'></i>
              <span class="nav__name"><!--Profile--></span>
            </button>
          </li>

          <li class="nav__item">
            <button name="locker" class="nav__link <?php echo ($rank == "Admin") ? "" : "locker active-link" ?> transparent">
              <i class='bx bx-cabinet nav__icon'></i>
              <span class="nav__name"><!--Locker--></span>
            </button>
          </li>

          <?php
          if ($rank == "Admin") {
            echo '<li class="nav__item">
                      <button name="admin" class="nav__link transparent">
                          <i class="bx bx-table nav__icon"></i>
                        <span class="nav__name"><!--Locker--></span>
                      </button>
                    </li>';
          }
          ?>

          <li class="nav__item">
            <a href="../../logout.php" class="nav__link">
              <i class='bx bx-log-out nav__icon'></i>
              <span class="nav__name"><!--Logout--></span>
            </a>
          </li>
        </ul>
      </div>
    </form>
    <img src="../../logo.svg" alt="" class="nav__img">
  </nav>
</header>

<?php

if (isset($_POST['profil'])) {
  redirectHeader("/profile/settings/index.php");
} else if (isset($_POST['admin'])) {
  redirectHeader("/profile/admin/index.php");
} else if (isset($_POST['locker'])) {
  redirectHeader("/profile/index.php");
} else if (isset($_POST['students_list'])) {
  redirectHeader("/profile/admin/students_list.php");
} else if (isset($_POST['lockernumberPage'])) {
  redirectHeader("/profile/admin/lockernumber.php");
} else if (isset($_POST['stat'])) {
  redirectHeader("/profile/admin/stat.php");
}

function redirectHeader($path)
{
  header("Location: $path");
  exit;
}


function randomWelcomeMsg()
{
  $messages = [
    "Üdvözöllek a világunkban! 🌍",
    "Szuper, hogy itt vagy! 🎉",
    "Jó látni téged! 😊",
    "Remélem, jól érezted magad! 💫",
    "Legyen szép napod! ☀️",
    "Mosolyogj, mert mosolyogni jó! 😄",
    "Hálásak vagyunk, hogy eljöttél! 🙏",
    "Itt minden nap, remek nap lehet! 🌈",
    "Mi jót csinálsz? 👀",
    "Vigyázunk a dolgaidra! 🔐",
    "Sikeres félévet! 🎓",
  ];

  if (!isset($_SESSION['welcomeMessage'])) {
    $randomIndex = array_rand($messages);
    $_SESSION['welcomeMessage'] = $messages[$randomIndex];
  }

  return $_SESSION['welcomeMessage'];
}
?>