// BURGER
document.addEventListener("DOMContentLoaded", function () {
    const burger = document.querySelector('.burger');
    const menu = document.querySelector('.menu-mobile');
    if (burger && menu) {
        burger.addEventListener('click', function () {
            burger.classList.toggle('active');
            menu.classList.toggle('active');
        });
    }
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

// LIGHTBOX
document.addEventListener("DOMContentLoaded", function () {
    const lightbox = document.getElementById("fullscreen-lightbox");
    const lightboxImage = document.getElementById("lightbox-image");
    const closeBtn = document.querySelector(".lightbox-close");

    document.querySelectorAll(".icon-fullscreen").forEach(icon => {
        icon.addEventListener("click", function (e) {
            e.preventDefault();
            const img = this.closest(".photo-thumbnail-link").querySelector("img");
            if (img) {
                lightboxImage.src = img.src;
                lightbox.classList.remove("lightbox-hidden");
                lightbox.classList.add("lightbox-visible");
            }
        });
    });

    closeBtn.addEventListener("click", function () {
        lightbox.classList.remove("lightbox-visible");
        lightbox.classList.add("lightbox-hidden");
        lightboxImage.src = "";
    });

    lightbox.addEventListener("click", function (e) {
        if (e.target === lightbox) {
            lightbox.classList.remove("lightbox-visible");
            lightbox.classList.add("lightbox-hidden");
            lightboxImage.src = "";
        }
    });
});


