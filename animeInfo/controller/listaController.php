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

$lista = new Lista();

$idLista = $_POST['idLista'];

if (empty($idLista)) {
    header("Location: ./indexController.php");
}

if (isset($_POST['borrarAnimeLista'])) {
    $idAnime = $_POST['idAnime'];
    $lista->eliminarAnimeDeLista($idLista, $idAnime);
}

$nombreLista = $lista->obtenerNombreLista($idLista);

$anime = new Anime();
$animesLista = $anime->obtenerAnimesLista($_SESSION['usuario'], $idLista);

$css = "lista.css";

$title = "List - ".$nombreLista->__get('nombreLista')."";

require_once "../view/templates/head.php";
require_once "../view/templates/header.php";
require_once "../view/lista.php";
include_once "../view/templates/footer.php";
?>
