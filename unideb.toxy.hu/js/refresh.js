function RealTimeRefresh(path, divID) {
      // Az oldal betöltésekor
      window.addEventListener('load', function() {

        // Hallgatók listájának frissítése
        updateStudentList();
  
        // Periodikus frissítés beállítása
        setInterval(updateStudentList, 5000);
      });
  
      // Hallgatók listájának frissítése
      function updateStudentList() {
        // AJAX kérés az új hallgatók listájának lekérésére
        var xhr = new XMLHttpRequest();
        xhr.open('GET', path, true);
        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4 && xhr.status === 200) {
            // A hallgatók listájának frissítése
            document.getElementById(divID).innerHTML = xhr.responseText;
          }
        };
        xhr.send();
      }
}


function RealTimeRefreshCheckbox(path, divID) {
    var cbstate = {};

      // Az oldal betöltésekor
      window.addEventListener('load', function() {
        // A checkbox állapotok visszaállítása
        cbstate = JSON.parse(localStorage.getItem('CBState')) || {};

        // Hallgatók listájának frissítése
        updateStudentList();

        // Periodikus frissítés beállítása
        setInterval(updateStudentList, 2000);
      });

      // Hallgatók listájának frissítése
      function updateStudentList() {
        // AJAX kérés az új hallgatók listájának lekérésére
        var xhr = new XMLHttpRequest();
        xhr.open('GET',path, true);
        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4 && xhr.status === 200) {
            // A hallgatók listájának frissítése
            document.getElementById(divID).innerHTML = xhr.responseText;

            // Checkbox állapotok visszaállítása
            restoreCheckboxStates();
          }
        };
        console.log("refresh");
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
}

