$(document).ready(function () {
    // select del modal
    // elimino ña validacion del select cuando cambia el valor
    $('#selectBotMain').change(function () {
        const selectBotMain = $('#selectBotMain').get(0);
        selectBotMain.setCustomValidity("");
        selectBotMain.reportValidity();
    });
    $('#btn-select-bot-modal').click(function () {
        //traigo el igg id del bot selecionado desde el modal
        const selectBotMain = $('#selectBotMain').get(0);
        const igg_id = $('#selectBotMain').val();
        // Validar select
        if (selectBotMain.value === "") {
            selectBotMain.setCustomValidity("Debes seleccionar un bot.");
            selectBotMain.reportValidity();
            return;
        } else {
            selectBotMain.setCustomValidity("");
        }
        //cargo el bot selecionado
        load_bot_selected(igg_id);
    });


    //select del menu
    $('#selectBotMenu').change(function () {
        //cargo el bot selecionado
        load_bot_selected($(this).val());

    });


    function load_bot_selected(igg_id) {
        $.ajax({
            type: "POST",
            url: "../usuarios/controller/load_bot.php",
            data: { igg_id: igg_id },
            beforeSend() {
                $("#loader").removeClass("esconder");
                $("body").addClass("hidenn");
            },
            success(resp) {
                if (resp == 1) {
                    location.reload();
                } else {
                    $("#loader").addClass("esconder");
                    $("body").removeClass("hidenn");
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Se produjo un error al cargar la informacion del bot",
                    });
                }
            },

        });
    }
})