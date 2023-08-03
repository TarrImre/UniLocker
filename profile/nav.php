
<?php
  $sql = "SELECT * FROM users WHERE id='$_SESSION[id]'";
  $result = mysqli_query($conn, $sql);
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
      <a href="#" class="nav__logo">Hello<img style='position: absolute;margin-left:6px' src='../../icons/wave.png' width='25px' height='25px'><br><?php echo "<p class='font_bold'>$vname $kname</p>" ?><?php /* echo $_SESSION['UniPassCode']; */?></a>
      <form method="post">
        <div class="nav__menu" id="nav-menu">
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
    header("Location: /profile/settings/index.php");
    exit;
  } else if (isset($_POST['admin'])) {
    header("Location: /profile/admin/index.php");
    exit;
  } else if (isset($_POST['locker'])) {
    header("Location: /profile/index.php");
    exit;
  } else if (isset($_POST['options'])) {
    header("Location: /profile/admin/options.php");
    exit;
  } else if (isset($_POST['lockernumberPage'])) {
    header("Location: /profile/admin/lockernumber.php");
    exit;
  }
?>