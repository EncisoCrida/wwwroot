<?php
function botUser($conexion, $id_user) {
    try {
        $sql = "SELECT * FROM `tb_bots` WHERE `id_user` = :id_user";
        $ejecutar = $conexion->prepare($sql);
        $ejecutar->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $ejecutar->execute();
        $bots = $ejecutar->fetchAll(PDO::FETCH_ASSOC);
        return $bots;
    } catch (PDOException $e) {
        // Manejo de errores: registrar el error y retornar un array vacío
        error_log("Error en botUser: " . $e->getMessage());
        return false;
    }
}