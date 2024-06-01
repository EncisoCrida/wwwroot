<?php
// Iniciar la sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//recibo las variables que llegan por ajax
$igg_id = isset($_POST['igg_id']) ? $_POST['igg_id'] : "";
// cargo a una variable de secicion el igg_id del bot selecionado
$_SESSION['igg_id'] = $igg_id;
//marco la variable de secion que indica que ya se seleciono un bot
$_SESSION['botSelected'] = 1;

//imprimo 1 para decir que fue exitoso
echo 1;

?>