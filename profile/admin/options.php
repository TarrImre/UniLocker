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

<!--FELHASZNÁLÓK KEZELÉSE-->
<?php include('../nav.php');?>
<div class="middle">
  <h1 class="font_bold" style="font-size:1.75rem;margin-top:15px;">Hallgatók</h1><h2 class="font_medium" style="font-size:0.85rem;color:rgba(0,0,0,0.5);">Itt tudod kezelni a hallgatókat</h2>
  <br>
  <div id="student-info"></div>
  <div id="student-list"></div>



  <script src="https://code.jquery.com/jquery.js"></script>

  <script>
  (function() {
    // Lokális változók
    var cbstate = {};

    // Az oldal betöltésekor
    window.addEventListener('load', function() {
      // A checkbox állapotok visszaállítása
      cbstate = JSON.parse(localStorage.getItem('CBState')) || {};

      // Hallgatók listájának frissítése
      updateStudentList();

      // Periodikus frissítés beállítása
      setInterval(updateStudentList, 5000);
    });

    // Hallgatók listájának frissítése
    function updateStudentList() {
      // AJAX kérés az új hallgatók listájának lekérésére
      var xhr = new XMLHttpRequest();
      xhr.open('GET', '/profile/admin/load_students.php', true);
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          // A hallgatók listájának frissítése
          document.getElementById('student-list').innerHTML = xhr.responseText;

          // Checkbox állapotok visszaállítása
          restoreCheckboxStates();
        }
      };
      xhr.send();
    }

    // Checkbox állapotok visszaállítása
    function restoreCheckboxStates() {
      var checkboxes = document.querySelectorAll('.save-cb-state');
      checkboxes.forEach(function(checkbox) {
        checkbox.checked = cbstate[checkbox.name] || false;
        checkbox.addEventListener('change', function(event) {
          cbstate[checkbox.name] = checkbox.checked;
          localStorage.setItem('CBState', JSON.stringify(cbstate));
        });
      });
    }
    
  })();
  </script>
</div>
<?php
} else {
  echo '<button class="button"><a href="../../index.html">Lépj be!</a></button>';
  exit;
}
?>
</body>
</html>