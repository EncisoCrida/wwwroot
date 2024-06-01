<?php
// Iniciar la sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir archivos necesarios
include_once ("../../config.php");

$opcion = isset($_POST['opcion']) ? $_POST['opcion'] : 1;

if ($opcion == 1) { // si la opcion es igual a 1 cargo la configuracion del bot para mostrarsela al usuario
    // Ruta al archivo JSON
    $jsonFile = $ruta_bots . $_SESSION['igg_id'] . '/settings.json';
    // Lee el contenido del archivo JSON
    $jsonData = file_get_contents($jsonFile);
    print_r($jsonData);
}else{ // si la opcion es 2 es para guardar cambios
    // Ruta al archivo settings.json
    $settingsFile = $ruta_bots . $_SESSION['igg_id'] . '/settings.json';
    
    // Leer el contenido del archivo settings.json
    $settingsJson = file_get_contents($settingsFile);
    
    // Decodificar el JSON en un array asociativo
    $settings = json_decode($settingsJson, true);
    
    // Actualizar los valores con los datos recibidos del formulario
    
    // Pergaminos y misiones
    $settings['questSettings']['autoTurfQuest'] = isset($_POST['misionesTerritorio']) ? (bool)$_POST['misionesTerritorio'] : false;
    $settings['questSettings']['openAllAdminQuest'] = isset($_POST['pergaminosAdmin']) ? (bool)$_POST['pergaminosAdmin'] : false;
    $settings['questSettings']['autoAdminQuest'] = isset($_POST['misionesAdministrativas']) ? (bool)$_POST['misionesAdministrativas'] : false;
    $settings['questSettings']['openAllGuildQuest'] = isset($_POST['pergaminosGremio']) ? (bool)$_POST['pergaminosGremio'] : false;
    $settings['questSettings']['autoGuildQuest'] = isset($_POST['misionesGremio']) ? (bool)$_POST['misionesGremio'] : false;
    
    // Amuletos y estrellas
    $settings['questSettings']['useHolyStars'] = isset($_POST['usarEstrellasSagradas']) ? (bool)$_POST['usarEstrellasSagradas'] : false;
    $settings['questSettings']['useLuckTokens'] = isset($_POST['usarAmuletos']) ? (bool)$_POST['usarAmuletos'] : false;
    
    // Programar spam
    $settings['miscSettings']['scheduleBuildSpam'] = isset($_POST['spamAutomatico']) ? (bool)$_POST['spamAutomatico'] : false;
    $settings['miscSettings']['scheduleBuildSpamAmount'] = isset($_POST['CantidadSpamAutomatico']) ? (int)$_POST['CantidadSpamAutomatico'] : 20;
    $settings['miscSettings']['scheduleBuildSpamHours'] = isset($_POST['spanXhoras']) ? (int)$_POST['spanXhoras'] : 6;
    
    // Otros ajustes
    $settings['connectionSettings']['otherLoginTime'] = isset($_POST['tiempoReconexion']) ? (int)$_POST['tiempoReconexion'] : 600;
    $settings['miscSettings']['autoOpenChests'] = isset($_POST['openAllCheats']) ? (bool)$_POST['openAllCheats'] : false;
    $settings['questSettings']['adventureLog'] = isset($_POST['diarioDeAventura']) ? (bool)$_POST['diarioDeAventura'] : false;
    $settings['miscSettings']['autoAttackFireTrial'] = isset($_POST['pruebaDeFuego']) ? (bool)$_POST['pruebaDeFuego'] : false;
    
    // Codificar de nuevo el array a JSON
    $newSettingsJson = json_encode($settings, JSON_PRETTY_PRINT);
    
    // Guardar el JSON actualizado en el archivo settings.json
    file_put_contents($settingsFile, $newSettingsJson);
    
    echo "Los cambios se han guardado correctamente.";
}
?>