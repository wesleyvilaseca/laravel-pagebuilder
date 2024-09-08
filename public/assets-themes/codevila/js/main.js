jQuery(document).ready(function ($) {
  //FIXED HEADER
  window.onscroll = function () {
    if (window.pageYOffset > 140) {
      $("#header").addClass("active");
    } else {
      $("#header").removeClass("active");
    }
  };

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

/**
 * dinamic links
 */

function get_link(route, classid) {
  let imageroute = site_url + '/' + route;
  return $(`.${classid}`).attr('src', imageroute);
}

document.cookie.indexOf("cookieaccepted") < 0 && (document.getElementById("cookie-notice").style.visibility = "visible");
function acceptCookie() {
  document.cookie = "cookieaccepted=1; expires=Thu, 18 Dec 2030 12:00:00 UTC; path=/", document.getElementById("cookie-notice").style.visibility = "hidden"
}

function myFunction() {
  document.getElementById("cookie-notice").style.visibility = "hidden";
}
