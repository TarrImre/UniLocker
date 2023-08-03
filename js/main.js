
/*=============== SCROLL SECTIONS ACTIVE LINK ===============*/
const sections = document.querySelectorAll('section[id]')

function scrollActive() {
  const scrollX = window.pageXOffset;

  sections.forEach((current) => {
    const sectionWidth = current.offsetWidth,
      sectionLeft = current.offsetLeft-1,
      sectionId = current.getAttribute('id');

    if (scrollX > sectionLeft && scrollX <= sectionLeft + sectionWidth) {
      document.querySelector('.nav__menu a[href*=' + sectionId + ']').classList.add('active-link');
    } else {
      document.querySelector('.nav__menu a[href*=' + sectionId + ']').classList.remove('active-link');
    }
  });
}

window.addEventListener('scroll', scrollActive);

/*=============== CHANGE BACKGROUND HEADER ===============*/
function scrollHeader() {
  const header = document.getElementById('header');
  // When the scroll is greater than 80 viewport width, add the scroll-header class to the header tag
  if (this.scrollX >= 80) header.classList.add('scroll-header');
  else header.classList.remove('scroll-header');
}

window.addEventListener('scroll', scrollHeader);
