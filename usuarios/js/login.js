$(document).ready(function () {
  $("#usuario, #clave").keyup(function () {
    var username = $("#usuario").val().trim();
    var password = $("#clave").val().trim();

    // Validar el campo de usuario (no vacío y solo números)
    if (username === "") {
      $("#usuario")
        .get(0)
        .setCustomValidity("El campo de usuario no puede estar vacío.");
    } else {
      $("#usuario").get(0).setCustomValidity("");
    }

    // Validar el campo de contraseña (no vacío)
    if (password === "") {
      $("#clave")
        .get(0)
        .setCustomValidity("La contraseña no puede estar vacía.");
    } else {
      $("#clave").get(0).setCustomValidity("");
    }
  });
  /* envio de datos por ajaax para la validacion */
  $("#login_user").on("submit", function (e) {
    e.preventDefault();
    var username = $("#usuario").val().trim();
    var password = $("#clave").val().trim();

    // Validar el campo de usuario (no vacío y solo números)
    if (username === "") {
      $("#usuario")
        .get(0)
        .setCustomValidity("El campo de usuario no puede estar vacío.");
      $("#usuario")[0].reportValidity();
      return;
    } else {
      $("#usuario").get(0).setCustomValidity("");
    }

    // Validar el campo de contraseña (no vacío)
    if (password === "") {
      $("#clave")
        .get(0)
        .setCustomValidity("La contraseña no puede estar vacía.");
      $("#clave")[0].reportValidity();
      return;
    } else {
      $("#clave").get(0).setCustomValidity("");
    }
    var formLogin = new FormData(document.getElementById("login_user"));
    $.ajax({
      type: "POST",
      url: "usuarios/controller/login.php",
      data: formLogin,
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function () {
        $("#loader_app").removeClass("esconder");
        $("body").addClass("hidenn");
      },
      success: function (resp) {
        console.log(resp);
        switch (resp) {
          case "0101s":
            window.location.href = "inicio/";
            break;
          case "0102w":
            $("#loader_app").addClass("esconder");
            $("body").removeClass("hidenn");
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "la clave es incorrecta",
            });
            break;
          case "0103r":
            $("#loader_app").addClass("esconder");
            $("body").removeClass("hidenn");
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "El usuario no existe",
            });
            break;

          default:
            break;
        }
      },
      complete: function () {},
    });
    return false;
  });
});
