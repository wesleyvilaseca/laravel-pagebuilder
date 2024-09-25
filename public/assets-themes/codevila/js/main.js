jQuery(document).ready(function ($) {
  //FIXED HEADER
  // window.onscroll = function () {
  //   if (window.pageYOffset > 140) {
  //     $("#header").addClass("active");
  //   } else {
  //     $("#header").removeClass("active");
  //   }
  // };

  //ISOTOPE
  let btns = $("#servicos .button-group button");

  btns.click(function (e) {
    $("#servicos .button-group button").removeClass("active");
    e.target.classList.add("active");

    let selector = $(e.target).attr("data-filter");
    $("#servicos .grid").isotope({
      filter: selector,
    });
  });

  $(window).on("load", function () {
    $("#servicos .grid").isotope({
      filter: "*",
    });
  });

  //MAGNIFY
  $(".grid .popup-link").magnificPopup({
    type: "image",
    gallery: {
      enabled: true,
      tPrev: "Anterior",
      tNext: "Próxima",
      tCounter: "%curr% de %total%",
    },
  });

  //OWL
  $(".owl-carousel").owlCarousel({
    loop: false,
    margin: 30,
    autoplay: true,
    autoplayTimeout: 6000,
    dots: true,
    lazyLoad: true,
    nav: false,
    responsive: {
      0: {
        items: 1,
      },
      600: {
        items: 1,
      },
      1000: {
        items: 2,
      },
    },
  });
});


function checkCookie() {
  const element = document.getElementById("cookie-notice");
  if (!element) return;

  const cookieExists = document.cookie.split(';').some((item) => item.trim().startsWith('cookieaccepted=1'));

  if (cookieExists) {
      element.style.visibility = "hidden";
  } else {
      element.style.visibility = "visible";
  }
}

function acceptCookie() {
  const now = new Date();
  const expirationDate = new Date(now);
  expirationDate.setFullYear(now.getFullYear() + 1);

  document.cookie = `cookieaccepted=1; expires=${expirationDate.toUTCString()}; path=/`;
  document.getElementById("cookie-notice").style.visibility = "hidden";
}

window.onload = checkCookie;