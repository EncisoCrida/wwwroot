window.onload = function () {
    $.ajax({
        type: "POST",
        url: "../usuarios/controller/select_bot.php",
        success(resp) {
            alert(resp);
            console.log(resp);
            var data = JSON.parse(resp);
            if (data.respuesta !== "1") {
                //select del modal
                var selectBotMain = $('#selectBotMain');
                selectBotMain.find('option:gt(0)').remove();
                data.forEach(function (bot) {
                    var option = $('<option></option>')
                        .attr('value', bot.igg_id)
                        .text(bot.name_bot);
                    selectBotMain.append(option);
                });
                $('#modalSeletBot').modal('show');
                $("#loader").addClass("esconder");
                $("body").removeClass("hidenn");
            } else {
                $("#loader").addClass("esconder");
                $("body").removeClass("hidenn");
            }
        },
        error: function (xhr, status, error) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Se produjo un error inesperado en el servidor",
            });
        }
    });
}
