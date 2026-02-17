$(document).ready(function () {
  // Navbar toggle functionality for mobile
  $("#navbarToggle").on("click", function () {
    $(".navbar-menu").toggleClass("active");
  });

  // Close menu when clicking on a link
  $(".navbar-link").on("click", function () {
    if (window.innerWidth <= 768) {
      $(".navbar-menu").removeClass("active");
    }
  });

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