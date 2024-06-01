$(document).ready(function () {
    //cargo la informacion del los ajustes generales de bot
    $.ajax({
        type: "POST",
        url: "../usuarios/controller/aGenerales.php",
        beforeSend() {
            $("#loader").removeClass("esconder");
            $("body").addClass("hidenn");
        },
        success(resp) {
            // console.log(resp);
            var data = JSON.parse(resp);
            //pergaminos de misiones
            $("#misionesDeTerritorio").prop('checked', data['questSettings'].autoTurfQuest);
            $("#pergaminosAdministrativas").prop('checked', data['questSettings'].openAllAdminQuest);
            $("#misionesAdministrativas").prop('checked', data['questSettings'].autoAdminQuest);
            $("#pergaminosDeGremio").prop('checked', data['questSettings'].openAllGuildQuest);
            $("#MisionesDeGremio").prop('checked', data['questSettings'].autoGuildQuest);

            //amuletos y estrellas
            $("#estrellasSagradas").prop('checked', data['questSettings'].useHolyStars);
            $("#usarAmuletos").prop('checked', data['questSettings'].useLuckTokens);

            //spam automatico
            $("#spamAutomatico").prop('checked', data['miscSettings'].scheduleBuildSpam);
            $("#CantidadSpamAutomatico").val(data['miscSettings'].scheduleBuildSpamAmount);
            $("#spanXhoras").val(data['miscSettings'].scheduleBuildSpamHours);

            //otros ajustes
            $("#tiempoReconexion").val(data['connectionSettings'].otherLoginTime);
            $("#openAllCheats").prop('checked', data['miscSettings'].autoOpenChests);
            $("#diarioDeAventura").prop('checked', data['questSettings'].adventureLog);
            $("#pruebaDeFuego").prop('checked', data['miscSettings'].autoAttackFireTrial);


            $("#loader").addClass("esconder");
            $("body").removeClass("hidenn");

        },
        error: function (xhr, status, error) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Se produjo un error inesperado en el servidor",
            });
            $("#loader").addClass("esconder");
            $("body").removeClass("hidenn")
        }
    });

    $('#FormAgenerales').submit(function (e) {
        e.preventDefault();
        var formLogin = new FormData(document.getElementById("FormAgenerales"));
        formLogin.append("opcion", 2);
        $.ajax({
            type: "POST",
            url: "../usuarios/controller/aGenerales.php",
            data: formLogin,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $("#loader").removeClass("esconder");
                $("body").addClass("hidenn");
            },
            success(resp) {
                console.log(resp);

                $("#loader").addClass("esconder");
                $("body").removeClass("hidenn");
                // alert de exito
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-center",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    },
                });
                Toast.fire({
                    icon: "success",
                    title: "Cambios Guardados con exito",
                });
            }
        })
    })
})