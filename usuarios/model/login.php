<?php

function buscarUsuario($conexion, $usuario)
{
    try {
        $sql = "SELECT buscarUsuario(:usuario) as resultado";
        $ejecutar = $conexion->prepare($sql);
        $ejecutar->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $ejecutar->execute();
        $salida = $ejecutar->fetch(PDO::FETCH_ASSOC);
        return $salida['resultado'];
    } catch (PDOException $e) {
        // Manejo de errores
        error_log("Error en buscarUsuario: " . $e->getMessage());
        return 0;
    }
}

function verificarClave($conexion, $usuario, $clave)
{
    try {
        $sql = "SELECT buscarClave(:usuario) as resultado";
        $ejecutar = $conexion->prepare($sql);
        $ejecutar->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $ejecutar->execute();
        $claveRe = $ejecutar->fetch(PDO::FETCH_ASSOC);
        $claveDB = $claveRe['resultado'];

        return password_verify($clave, $claveDB) ? 1 : 0;
    } catch (PDOException $e) {
        // Manejo de errores
        error_log("Error en verificarClave: " . $e->getMessage());
        return 0;
    }
}

function getIdUser($conexion, $id_user)
{
    try {
        $sql = "SELECT * FROM tb_users WHERE id_user = :id_user";
        $ejecutar = $conexion->prepare($sql);
        $ejecutar->bindParam(':id_user', $id_user, PDO::PARAM_STR);
        $ejecutar->execute();
        $salida = $ejecutar->fetch(PDO::FETCH_ASSOC);
        return $salida;
    } catch (PDOException $e) {
        // Manejo de errores
        error_log("Error en getIdUser: " . $e->getMessage());
        return null;
    }
}
function getUser($conexion, $usuario)
{
    try {
        $sql = "SELECT * FROM tb_users WHERE usuario = :usuario";
        $ejecutar = $conexion->prepare($sql);
        $ejecutar->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $ejecutar->execute();
        $salida = $ejecutar->fetch(PDO::FETCH_ASSOC);
        return $salida;
    } catch (PDOException $e) {
        // Manejo de errores
        error_log("Error en getIdUser: " . $e->getMessage());
        return null;
    }
}



// Método para generar un token aleatorio de una longitud dada
function getToken($length)
{
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    $max = strlen($codeAlphabet) - 1;
    for ($i = 0; $i < $length; $i++) {
        $token .= $codeAlphabet[cryptoRandSecure(0, $max)];
    }
    return $token;
}

// Método para generar un número aleatorio seguro usando OpenSSL
function cryptoRandSecure($min, $max)
{
    $range = $max - $min;
    if ($range < 1)
        return $min;
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1;
    $bits = (int) $log + 1;
    $filter = (int) (1 << $bits) - 1;
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter;
    } while ($rnd >= $range);
    return $min + $rnd;
}

function getTokenByUsername($conexion, $usuario, $is_expired)
{
    try {
        $sql = "SELECT * FROM login_token WHERE usuario = :usuario AND is_expired = :is_expired";
        $ejecutar = $conexion->prepare($sql);
        $ejecutar->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $ejecutar->bindParam(':is_expired', $is_expired, PDO::PARAM_INT);
        $ejecutar->execute();
        return $ejecutar->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Manejo de errores
        error_log("Error en getTokenByUsername: " . $e->getMessage());
        return null;
    }
}

function markAsExpired($conexion, $tokenId) // modifique esta funcion para que elimine el token
{
    try {
        $sql = "DELETE FROM login_token WHERE id = :tokenId";
        $ejecutar = $conexion->prepare($sql);
        $ejecutar->bindParam(':tokenId', $tokenId, PDO::PARAM_INT);
        $ejecutar->execute();
        return true;
    } catch (PDOException $e) {
        // Manejo de errores
        error_log("Error en deleteToken: " . $e->getMessage());
        return false;
    }
}

function insertToken($conexion, $username, $random_password_hash, $random_selector_hash, $expiry_date)
{
    try {
        $sql = "INSERT INTO login_token (usuario, password_hash, selector_hash, expiry_date) VALUES (:usuario, :random_password_hash, :random_selector_hash, :expiry_date)";
        $ejecutar = $conexion->prepare($sql);
        $ejecutar->bindParam(':usuario', $username, PDO::PARAM_STR);
        $ejecutar->bindParam(':random_password_hash', $random_password_hash, PDO::PARAM_STR);
        $ejecutar->bindParam(':random_selector_hash', $random_selector_hash, PDO::PARAM_STR);
        $ejecutar->bindParam(':expiry_date', $expiry_date, PDO::PARAM_STR);
        $ejecutar->execute();
        return true;
    } catch (PDOException $e) {
        // Manejo de errores
        error_log("Error en insertToken: " . $e->getMessage());
        return false;
    }
}
// elimino las cookies de autenticaciones
function clearAuthCookie()
{
    $cookieParams = session_get_cookie_params();
    if (isset($_COOKIE["login_usuario"])) {
        setcookie("login_usuario", "", time() - 3600, $cookieParams["path"], $cookieParams["domain"], true, true);
        unset($_COOKIE["login_usuario"]);
    }
    if (isset($_COOKIE["random_password"])) {
        setcookie("random_password", "", time() - 3600, $cookieParams["path"], $cookieParams["domain"], true, true);
        unset($_COOKIE["random_password"]);
    }
    if (isset($_COOKIE["random_selector"])) {
        setcookie("random_selector", "", time() - 3600, $cookieParams["path"], $cookieParams["domain"], true, true);
        unset($_COOKIE["random_selector"]);
    }
}

?>