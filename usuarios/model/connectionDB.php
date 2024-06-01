<?php
class Connection
{
    public static function Conectar($host, $user_db, $name_db, $pass)
    {
        define('server', $host);
        define('name_db', $name_db);
        define('user', $user_db);
        define('password', $pass);
        $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
        try {
            $connection = new PDO("mysql:host=" . server . "; dbname=" . name_db, user, password, $options);
            return $connection;
        } catch (Exception $e) {
            die("El error de connection es: " . $e->getMessage());
        }
    }
}
