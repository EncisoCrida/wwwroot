<?php
// Iniciar la sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir archivos necesarios
include ("../model/login.php");
include_once ("../model/connectionDB.php");
include_once ("../../config.php");

// Crear conexión a la base de datos
$objeto = new Connection();
$conexion = $objeto->Conectar($host, $user_db, $name_db, $pass);

// Obtener datos del usuario desde el formulario
$usuario = $_POST['usuario'];
$clave = $_POST['clave'];
$remember = isset($_POST['remember']) ? $_POST['remember'] : "";

// Verificar usuario y contraseña
if (buscarUsuario($conexion, $usuario) == 1) {
    if (verificarClave($conexion, $usuario, $clave) == 1) {
        // Recuperar datos del usuario
        $_SESSION['user'] = getUser($conexion, $usuario);
        $_SESSION['Modal'] = 0;
        // Código de éxito
        echo "0101s";

        // Manejar la funcionalidad de 'Recordarme'
        if ($remember === 'on') {
            // Limpiar las cookies de autenticación actuales
            clearAuthCookie();

            $cookie_expiration_time = time() + (30 * 24 * 60 * 60); // 30 días
            setcookie("login_usuario", $usuario, $cookie_expiration_time, "/");
            // Generar tokens aleatorios
            $random_password = getToken(16);
            setcookie("random_password", $random_password, $cookie_expiration_time, "/");

            $random_selector = getToken(32);
            setcookie("random_selector", $random_selector, $cookie_expiration_time, "/");

            // Hashear los tokens
            $random_password_hash = password_hash($random_password, PASSWORD_DEFAULT);
            $random_selector_hash = password_hash($random_selector, PASSWORD_DEFAULT);

            // Encriptar el ID del usuario
            //nota importante voy a usar el randor selector hast como clave de sifrado pero se puede usar otra
            $encrypted_user_id = openssl_encrypt($_SESSION['user']['id_user'], 'AES-128-ECB', $random_selector_hash);
            setcookie("user_id", $encrypted_user_id, $cookie_expiration_time, "/");

            $expiry_date = date("Y-m-d H:i:s", $cookie_expiration_time);

            // Marcar el token existente como expirado
            $userToken = getTokenByUsername($conexion, $usuario, 0);
            if (!empty($userToken[0]["id"])) {
                markAsExpired($conexion, $userToken[0]["id"]);
            }

            // Insertar el nuevo token en la base de datos
            insertToken($conexion, $usuario, $random_password_hash, $random_selector_hash, $expiry_date);
        } else {
            // Limpiar las cookies de autenticación
            clearAuthCookie();
        }

    } else {
        // Código de contraseña incorrecta
        echo "0102w";
    }
} else {
    // Código de usuario no encontrado
    echo "0103r";
}
?>