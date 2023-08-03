
function showSendButton(event) {
  const inputElems = document.querySelectorAll('.save-cb-state');
  const overlay = document.querySelector('.overlay');
  let anyChecked = false;

  // Ellenőrizzük, hogy legalább egy input mező be van-e pipálva vagy sem
  inputElems.forEach((inputElem) => {
    if (inputElem.checked) {
      anyChecked = true;
      return;
    }
  });

  if (anyChecked) {
    // Ha bármelyik be van pipálva, megjelenítjük az overlay-t
    overlay.style.display = 'block';
  }

  // Eltávolítjuk az eseményfigyelőt az overlay-ről, hogy ne reagáljon a kattintásra
  overlay.removeEventListener('click', overlayClickHandler);

  // Hozzáadjuk az eseményfigyelőt az input mezőkhöz, hogy a checkboxra kattintáskor az overlay elrejtődjön
  inputElems.forEach((inputElem) => {
    inputElem.addEventListener('click', checkboxClickHandler);
  });
}

function checkboxClickHandler(event) {
  const inputElems = document.querySelectorAll('.save-cb-state');
  const overlay = document.querySelector('.overlay');

  let anyChecked = false;

  // Ellenőrizzük, hogy legalább egy input mező be van-e pipálva vagy sem
  inputElems.forEach((inputElem) => {
    if (inputElem.checked) {
      anyChecked = true;
      return;
    }
  });

  if (!anyChecked) {
    // Ha egyik sincs bepipálva, elrejtjük az overlay-t
    overlay.style.display = 'none';
  }
}

// Eseményfigyelő függvény az overlay kattintásra történő reagáláshoz
function overlayClickHandler() {
  const inputElems = document.querySelectorAll('.save-cb-state');
  const overlay = document.querySelector('.overlay');

  // Minden input mezőt visszaállítunk
  inputElems.forEach((inputElem) => {
    inputElem.checked = false;
  });

  // Az overlay-t elrejtjük
  overlay.style.display = 'none';
}
