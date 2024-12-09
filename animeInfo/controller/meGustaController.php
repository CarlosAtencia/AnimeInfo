<?php
ob_start();
session_start();

require_once "../autoload.php";
require_once '../model/funciones.inc.php';

comprobarSesionIniciada(false);

$css = "meGusta.css";
$title = "Favorites";

// Obtenemos el ID del usuario desde la sesión
if (isset($_SESSION['usuario'])) {
    inactividadUsuario();
    $idUsuario = $_SESSION['usuario'];
    $rol = $_SESSION['rol'];    

    $usuario = comprobarRol($idUsuario, $rol);
}


$anime = new Anime();

// En caso de quitar un anime de "me gusta"
if (isset($_POST['quitarMeGusta'])) {
    $idAnime = $_POST['idAnime'];

    $anime->quitarMeGusta($idAnime, $idUsuario);
    
    header("Location: ./meGustaController.php");
    exit();
}

$animesMeGusta = $anime->mostrarAnimesMeGusta($idUsuario);

require_once "../view/templates/head.php";
require_once "../view/templates/header.php";
require_once "../view/meGusta.php";
include_once "../view/templates/footer.php";
?>
