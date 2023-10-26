function countCheckboxes() {
    var a = document.forms["main"];
    var x = a.querySelectorAll('input[type="checkbox"]:checked');
    console.log(x.length);
    if (x.length > 1) {
      Swal.fire({
        icon: 'error',
        title: 'Maximum 1 szekrényt választhatsz!',
        text: 'Próbáld újra!',
        timer: 1500,
        allowOutsideClick: false,
        showCancelButton: false,
        showConfirmButton: false
      });
      event.preventDefault();
    }
  }