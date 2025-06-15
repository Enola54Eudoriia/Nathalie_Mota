document.addEventListener('DOMContentLoaded', function () {
  const burger = document.querySelector('.burger');
  const menu = document.querySelector('.menu-mobile');

  burger.addEventListener('click', function () {
    burger.classList.toggle('active');
    menu.classList.toggle('active');
  });
});
