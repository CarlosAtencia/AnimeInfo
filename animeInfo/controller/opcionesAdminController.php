<?php
ob_start();
session_start();

require_once "../autoload.php";
require_once '../model/funciones.inc.php';

$css = "opcionesAdmin.css";
$title = "Admin Options";

// Obtenemos el ID del usuario desde la sesión
if (isset($_SESSION['usuario'])) {
    inactividadUsuario();
    $idUsuario = $_SESSION['usuario'];
    $rol = $_SESSION['rol'];    

    $usuario = comprobarRol($idUsuario, $rol);
}

if ($rol !== "admin") {
    header("Location: ./indexController.php");
}

$admin = new Admin();

$clientes = $admin->obtenerClientes();

if (isset($_POST['borrarUsuario'])) {
    $idUsuario = $_POST['idUsuario'];

    $admin->borrarCuenta($idUsuario);

    header("Location: opcionesAdminController.php");
    exit();
}

require_once "../view/templates/head.php";
require_once "../view/templates/header.php";
require_once "../view/opcionesAdmin.php";
include_once "../view/templates/footer.php";
?>
