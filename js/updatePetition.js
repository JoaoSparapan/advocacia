function goBack() {
  window.history.back();
}

$(document).ready(() => {
  $("select").formSelect();
  function changeMobile() {
    const inputsComputer = $(".row.computer .input-field input");
    const inputsMobile = $(".row.mobile .input-field input");
    for (let i = 0; i < inputsComputer.length; i++) {
      inputsComputer[i].removeAttribute("required");
      inputsComputer[i].disabled = true;
      inputsMobile[i].setAttribute("required", "");
      inputsMobile[i].disabled = false;
    }

    $(".row.computer .input-field select")[0].removeAttribute("required");
    $(".row.computer .input-field select").prop("disabled", true);
    $(".row.mobile .input-field select")[0].setAttribute("required", "");
    $(".row.mobile .input-field select").prop("disabled", false);
    $("select").formSelect();
  }
  function changeComputer() {
    const inputsMobile = $(".row.mobile .input-field input");
    const inputsComputer = $(".row.computer .input-field input");

    for (let i = 0; i < inputsMobile.length; i++) {
      inputsMobile[i].removeAttribute("required");
      inputsMobile[i].disabled = true;
      inputsComputer[i].setAttribute("required", "");
      inputsComputer[i].disabled = false;
    }
    $(".row.mobile .input-field select")[0].removeAttribute("required");
    $(".row.mobile .input-field select").prop("disabled", true);
    $(".row.computer .input-field select")[0].setAttribute("required", "");
    $(".row.computer .input-field select").prop("disabled", false);
    $("select").formSelect();
  }
  window.addEventListener("resize", () => {
    if (window.innerWidth <= 750) {
      changeMobile();
    } else {
      changeComputer();
    }
  });

  if (window.innerWidth <= 750) {
    changeMobile();
  } else {
    changeComputer();
  }
});
