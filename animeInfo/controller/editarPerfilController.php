<?php

ob_start();
session_start();

require_once "../autoload.php";
require_once '../model/funciones.inc.php';

comprobarSesionIniciada(false);

$css = "editarPerfil.css";
$title = "Edit Profile";

// Obtenemos el ID del usuario desde la sesión
if (isset($_SESSION['usuario'])) {
    inactividadUsuario();
    $idUsuario = $_SESSION['usuario'];
    $rol = $_SESSION['rol'];    

    $usuario = comprobarRol($idUsuario, $rol);
}

$usuario->obtenerUsuario($idUsuario);

// En caso de editar perfil
if (isset($_POST['editarPerfil'])) {
    $resultadoFormulario = $usuario->editarPerfil($_SESSION['usuario'],$_POST['nombreUsuario'], $_POST['correo'], $_POST['passwd'],$_FILES['img']);
} else {
    $resultadoFormulario = "";
}


require_once "../view/templates/head.php";
require_once "../view/templates/header.php";
require_once "../view/editarPerfil.php";
include_once "../view/templates/footer.php";
