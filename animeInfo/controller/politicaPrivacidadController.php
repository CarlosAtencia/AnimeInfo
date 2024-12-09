<?php
ob_start();
session_start();

require_once "../autoload.php";
require_once '../model/funciones.inc.php';

// Obtenemos el ID del usuario desde la sesión
if (isset($_SESSION['usuario'])) {
    inactividadUsuario();
    $idUsuario = $_SESSION['usuario'];
    $rol = $_SESSION['rol'];    

    $usuario = comprobarRol($idUsuario, $rol);
}

$css = "politicaPrivacidad.css";
$title = "Privacy Policy";

require_once "../view/templates/head.php";
require_once "../view/templates/header.php";
require_once "../view/politicaPrivacidad.php";
include_once "../view/templates/footer.php";
