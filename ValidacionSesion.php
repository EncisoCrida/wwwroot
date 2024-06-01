<?php
// iniciamos la variable de sesion 
if (!isset($_SESSION))
    session_start();
//llamo al modelo para incluir las peticiones a la base de datos y verificaciones
include ("usuarios/model/login.php");
//se llama la conexion a la base de datos
include_once ("usuarios/model/connectionDB.php");
//llamo la configuracion para la base de datos
include_once ("config.php");
$objeto = new Connection();
$conexion = $objeto->Conectar($host, $user_db, $name_db, $pass);

// Obtener la fecha y hora actual
$current_time = time(); // Obtener el tiempo actual en formato UNIX timestamp
$current_date = date("Y-m-d H:i:s", $current_time); // Formatear el tiempo actual como una cadena de fecha y hora

// Establecer la expiración de la cookie por 1 mes
$cookie_expiration_time = $current_time + (30 * 24 * 60 * 60); // Se suman 30 días en segundos para obtener la fecha de expiración de la cookie

$isLoggedIn = false; // Variable para rastrear si el usuario está autenticado o no

// Verificar si existe una sesión de usuario iniciada y redirigir si la sesión existe
if (!empty($_SESSION["user"])) {
    $isLoggedIn = true; // El usuario está autenticado
}
// Verificar si existen cookies de autenticación
else if (!empty($_COOKIE["login_usuario"]) && !empty($_COOKIE["random_password"]) && !empty($_COOKIE["random_selector"])) {
    // Inicializar variables para verificar la autenticación de la cookie
    $isPasswordVerified = false; // Variable para verificar si la contraseña aleatoria es válida
    $isSelectorVerified = false; // Variable para verificar si el selector aleatorio es válido
    $isExpiryDateVerified = false; // Variable para verificar si la fecha de expiración de la cookie es válida

    // Obtener el token de inicio de sesión para el nombre de usuario de la cookie
    $userToken = getTokenByUsername($conexion, $_COOKIE["login_usuario"], 0); // Obtener el token de inicio de sesión de la base de datos

    // Validar la contraseña aleatoria con la contraseña almacenada en la base de datos
    if (password_verify($_COOKIE["random_password"], $userToken[0]["password_hash"])) {
        $isPasswordVerified = true; // La contraseña aleatoria es válida
    }

    // Validar el selector aleatorio con el selector almacenado en la base de datos
    if (password_verify($_COOKIE["random_selector"], $userToken[0]["selector_hash"])) {
        $isSelectorVerified = true; // El selector aleatorio es válido
    }

    // Verificar si la fecha de expiración de la cookie es posterior a la fecha actual
    if ($userToken[0]["expiry_date"] >= $current_date) {
        $isExpiryDateVerified = true; // La fecha de expiración de la cookie es válida
    }

    // Redirigir si todas las validaciones de las cookies son verdaderas
    // De lo contrario, marcar el token como expirado y limpiar las cookies
    if (!empty($userToken[0]["id"]) && $isPasswordVerified && $isSelectorVerified && $isExpiryDateVerified) {
        $isLoggedIn = true; // El usuario está autenticado
    } else {
        if (!empty($userToken[0]["id"])) {
            markAsExpired($conexion, $userToken[0]["id"]); // Marcar el token como expirado en la base de datos
        }
        // Limpiar las cookies de autenticación
        clearAuthCookie();
    }
}
?>