<?php

/**
 * class path
 * esta se encarga de dar la ruta del contenido que se va a incluir en la platilla principal 
 * autor: Enciso Crida @SASUKE
 */
class path
{

    /**
     * esta funcion se encarga de recibir la bariable seccion la cual representa un numero
     * depeniendo el numero asi mismo la funcion retorna un nombre de ruta el cual se va a mostrar al usuario
     * 
     * @param           num         numero de seccion
     * @return          text        retorna el nombre se seccion
     */

    function search_path($seccion)
    {
        $result = '';

        switch ($seccion) {
            case 0:
                $result = 'login.phtml';
                break;
            case 1:
                $result = 'home.phtml';
                break;
            case 2:
                $result = 'aGenerales.phtml';
                break;
            default:
                # code...
                break;
        }
                 
        return $result;
    }
    function name_path($seccion)
    {
        $result = '';

        switch ($seccion) {
            case 1:
                $result = 'Inicio';
                break;
            case 2:
                $result = 'Solicitudes';
                break;
            default:
                # code...
                break;
        }
                 
        return $result;
    }
}
