document.addEventListener('DOMContentLoaded', function () {
  const burger = document.querySelector('.burger');
  const menu = document.querySelector('.menu-mobile');

  burger.addEventListener('click', function () {
    burger.classList.toggle('active');
    menu.classList.toggle('active');
  });
});

document.addEventListener('DOMContentLoaded', function () {
  // Ouverture modale
  const openModalBtn = document.querySelector('.open-contact-modal');
  const modal = document.getElementById('contact-modal');

  if (openModalBtn && modal) {
    openModalBtn.addEventListener('click', function (e) {
      e.preventDefault();
      modal.classList.remove('hidden');
    });

    modal.addEventListener('click', function (e) {
      if (e.target === modal) {
        modal.classList.add('hidden');
      }
    });
  }
});
