<?php

/**
 * General Controller
 * bye: Enciso Crida @SASUKE
 */

/** si no existe una sesion se crea */
if (!isset($_SESSION))
    session_start();

require_once "ValidacionSesion.php";

if (!$isLoggedIn) {
    header("location:../login.php");
}
/**se verifica que exista la sesion usuario */
if (isset($_SESSION['user'])) {
    /** si existe la sesion usuario se verofoca que tengas un usuaeio */
    if ($_SESSION['user'] == '') {
        header("location:../login.php");
    } else {
        // segun el rol del usuario lo redireciono
        switch ($_SESSION['user']['rol']) {
            case 'ADMIN':
                header("location:../admin/");
                break;
            case 'USUARIO':
                // incluyo la configuracion
                include '../../config.php';
                break;
        }
    }
} else {
    header("location:../login.php");
}
//recibo la variable de seccion enviada por url
$seccion = $_GET['seccion'];

//llamado de la clase ruta
include 'path.php';
$new_object = new path();
$path = $new_object->search_path($seccion);
$name_path = $new_object->name_path($seccion);

// esta variable hace referencia a lo que se va a mostrar en pantalla
//la variable ruta es el nombre del archivo que se va a mostrar este nombre se trae mediante la clase "Ruta"
$contenido = "../view/$path";
require_once ("../view/template.phtml");
