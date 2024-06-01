<?php
if (!isset($_SESSION))
    session_start();

include ("../model/login.php");

//Clear Session
$_SESSION["user"] = "";
session_destroy();

// clear cookies
clearAuthCookie();

header("Location: ./");
?>