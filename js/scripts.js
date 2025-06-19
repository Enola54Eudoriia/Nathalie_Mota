document.addEventListener('DOMContentLoaded', function () {
  const burger = document.querySelector('.burger');
  const menu = document.querySelector('.menu-mobile');

  burger.addEventListener('click', function () {
    burger.classList.toggle('active');
    menu.classList.toggle('active');
  });
});

jQuery(document).ready(function($) {
  $('.open-contact-modal').on('click', function(e) {
    e.preventDefault();

    var ref = $(this).data('reference');
    $('#contact-modal').removeClass('hidden');

    var refField = $('#wpforms-18-field_4');
    if (refField.length) {
      refField.val(ref || '');
    }
  });

  $('#contact-modal').on('click', function(e) {
    if (e.target === this) {
      $(this).addClass('hidden');
    }
  });
});


