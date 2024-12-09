<?php 

ob_start();
session_start();

require_once "../autoload.php";
require_once '../model/funciones.inc.php';

comprobarSesionIniciada(false);

// Obtenemos el ID del usuario desde la sesión
if (isset($_SESSION['usuario'])) {
    inactividadUsuario();
    $idUsuario = $_SESSION['usuario'];
    $rol = $_SESSION['rol'];    

    $usuario = comprobarRol($idUsuario, $rol);
}

$usuario->obtenerUsuario($idUsuario);

if (isset($_POST['borrarCuenta'])) {
    $usuario->borrarCuenta($_SESSION['usuario']);
    header("Location: ./cerrarSesion.php");
    exit();
}

$css = "perfil.css";
$title = "Profile";

require_once "../view/templates/head.php";
require_once "../view/templates/header.php";
require_once "../view/perfil.php";
include_once "../view/templates/footer.php";

?>