document.addEventListener('DOMContentLoaded', function () {
  const burger = document.querySelector('.burger');
  const menu = document.querySelector('.menu-mobile');

  burger.addEventListener('click', function () {
    burger.classList.toggle('active');
    menu.classList.toggle('active');
  });
});

document.addEventListener('DOMContentLoaded', function () {
  const openModalBtns = document.querySelectorAll('.open-contact-modal');
  const modal = document.getElementById('contact-modal');

  openModalBtns.forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      modal.classList.remove('hidden');

      const ref = btn.getAttribute('data-reference');
      const refField = modal.querySelector('#wpforms-18-field_4');
      if (refField) {
        refField.value = ref || '';
      }
    });
  });

  modal.addEventListener('click', function (e) {
    if (e.target === modal) {
      modal.classList.add('hidden');
    }
  });
});

