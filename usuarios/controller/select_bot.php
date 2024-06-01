<?php
// Iniciar la sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir archivos necesarios
include ("../model/bots.php");
include_once ("../model/connectionDB.php");
include_once ("../../config.php");

// Crear conexión a la base de datos
$objeto = new Connection();
$conexion = $objeto->Conectar($host, $user_db, $name_db, $pass);

// id del usuario logeado
$id_user = $_SESSION['user']['id_user'];
// $_SESSION['botSelected'] = 0;
/** Se verifica que exista la sesión de bots */
if (isset($_SESSION['botSelected'])) {
    /** Si existe la sesión de bots se verifica que tenga los bots del usuario */
    if ($_SESSION['botSelected'] == 0) {
        // Traigo los bots del usuario para que puedan ser cargados al select
        $botDataBase = botUser($conexion, $id_user);
        $data = [];

        // Iterar sobre cada bot y obtener sus datos JSON
        foreach ($botDataBase as $bot) {
            $botInfo = datos_bots_json($bot['igg_id'], $bot['id_bot'], $ruta_bots);
            if (!isset($botInfo['error'])) {
                $data[] = $botInfo;
            }
        }
        $_SESSION['botsUser'] = $data;
    } else {
        // Si ya hay bot seleccionado regreso como respuesta 1 para omitir el lanzamiento del modal
        $data = [
            "respuesta" => "1"
        ];
    }
} else {
    // Traigo los bots del usuario para que puedan ser cargados al select
    $botDataBase = botUser($conexion, $id_user);
    $data = [];

    // Iterar sobre cada bot y obtener sus datos JSON
    foreach ($botDataBase as $bot) {
        $botInfo = datos_bots_json($bot['igg_id'], $bot['id_bot'], $ruta_bots);
        if (!isset($botInfo['error'])) {
            $data[] = $botInfo;
        }
    }
    $_SESSION['botsUser'] = $data;
}

/**
 * Función para obtener los datos del bot desde el archivo JSON
 */
function datos_bots_json($igg_id, $id_bot, $ruta_bots){
    // Ruta al archivo JSON
    $jsonFile = $ruta_bots . $igg_id . '/acc.json';

    // Lee el contenido del archivo JSON
    $jsonData = file_get_contents($jsonFile);

    // Decodifica el JSON en un array asociativo
    $data = json_decode($jsonData, true);

    // Verifica si la decodificación fue exitosa
    if (json_last_error() === JSON_ERROR_NONE) {
        // Accede al campo "Name"
        $name = $data['Name'];

        // Devuelve los datos en el formato esperado
        return [
            'igg_id' => $igg_id,
            'name_bot' => $name,
            'respuesta' => "0"
        ];
    } else {
        // Maneja el error de decodificación
        return [
            'error' => 'Error al decodificar el JSON: ' . json_last_error_msg()
        ];
    }
}

// Imprimir los datos codificados en JSON
echo json_encode($data);
?>
