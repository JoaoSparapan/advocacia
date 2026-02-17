$(document).ready(function () {
  // Navbar toggle functionality for mobile
  $("#navbarToggle").on("click", function () {
    $(".navbar-menu").toggleClass("active");
  });

  $(document).on("click", function (e) {
    if (!$(e.target).closest(".navbar-item.dropdown").length) {
      $(".dropdown-menu").removeClass("active");
    }
  });

  $(".navbar-item.dropdown > .navbar-link").on("click", function (e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).siblings(".dropdown-menu").toggleClass("active");
  });

  $(".dropdown-link").on("click", function () {
    $(".dropdown-menu").removeClass("active");
    if (window.innerWidth <= 768) {
      $(".navbar-menu").removeClass("active");
    }
  });

  $(".navbar-item:not(.dropdown) > .navbar-link").on("click", function () {
    if (window.innerWidth <= 768) {
      $(".navbar-menu").removeClass("active");
    }
  });

  var fullHeight = function () {
    $(".js-fullheight").css("height", $(window).height());
    $(window).resize(function () {
      $(".js-fullheight").css("height", $(window).height());
    });
  };
  fullHeight();

  $(".logout").click(function () {
    var root = location.protocol + "//" + location.host;
    console.log(root + "/advocacia/services/Controller/LogOut.php");
    $.ajax({
      url: root + "/advocacia/services/Controller/LogOut.php",
      success: function (result) {
        if (result == "true") {
          document.location.reload(true);
        } else {
          alert("Erro ao deslogar! ");
          console.log(result);
        }
      },
    });
  });
});
