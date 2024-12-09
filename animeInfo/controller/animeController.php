<?php

ob_start();
session_start();

require_once "../autoload.php";
require_once '../model/funciones.inc.php';

// Obtener el ID del usuario desde la sesión
if (isset($_SESSION['usuario'])) {
    inactividadUsuario();
    $idUsuario = $_SESSION['usuario'];
    $rol = $_SESSION['rol'];

    $usuario = comprobarRol($idUsuario, $rol);
}

// Obtenemos el ID del anime cuando accedemos a el en el index o la propia url
$id = $_GET['id'];

$idAnime = (int)$id; // Lo pasamos a int para que lo reconozca la api

$anime = new Anime();

if (empty($idAnime)) {
    header("Location: ./indexController.php");
    exit();
}

// Comprobamos primero si el id obtenido existe en la BBDD
if (!$anime->animeExiste($_GET['id'])) {
    // En caso de no existir hacemos una peticion a la api para obtener los datos y pasarlos a la BBDD
    $animeDetalles = $anime->obtenerAnimePorId($idAnime);
    $anime->agregarAnime($animeDetalles);
}

// Obtenemos el anime desde la base de datos
$animeObtenido = $anime->obtenerDetallesAnimeDB($_GET['id']);

// Titulo en el navegador
if ($animeObtenido) {
    $title = "Anime - " . $animeObtenido->__get("nombreAnime");
} else {
    $title = "Anime";
}

$css = "anime.css";

$lista = new Lista();

// Obtenemos las listas del usuario
if (isset($_SESSION['usuario'])) {
    $listas = $lista->obtenerListasPorUsuario($_SESSION["usuario"]);
}

$listasConAnime = $lista->obtenerListasConAnime($idAnime); // Obtenemos las listas que tenga el anime que estamos mostrando

// En caso de guardar el anime en alguna lista
if (isset($_POST['guardarEnLista'])) {
    $idAnime = $_POST['idAnime'];
    $idLista = $_POST['idLista'];

    if (is_null($idLista)) {
        header("Location: ./animeController.php?id=".$idAnime."");
        exit();
    }

    Lista::guardarAnimeLista($idLista, $idAnime);
}

// Comrpobamos si el usuario ya ha dado "Me gusta"
if (isset($_SESSION['usuario'])) {
    $tieneMeGusta = $anime->comprobarMeGusta($idAnime, $_SESSION['usuario']);
}

if (isset($_POST['meGusta'])) {
    $resultadoFormulario = $anime->darMeGusta($idAnime, $_SESSION['usuario']);
    
    header("Location: ./animeController.php?id=".$idAnime."");
    exit();
} else {
    $resultadoFormulario = '';
}

if (isset($_POST['quitarMeGusta'])) {
     $resultadoFormulario = $anime->quitarMeGusta($idAnime, $_SESSION['usuario']);
     
     header("Location: ./animeController.php?id=".$idAnime."");
     exit();
} else {
    $resultadoFormulario = '';
}

$comentario = new Comentario();

$comentarios = $comentario->mostrarComentariosPorAnime($idAnime);

if (isset($_SESSION['usuario'])) {
    if (isset($_POST['comentario'])) {
        // Obtenemos el comentario del usuario
        $comentarioTexto = $_POST['comentario']; 

        // Creamos y almacenamos el comentarios en la BBDD con el siguiente metodo
        $comentarioResultado = $comentario->crearComentario($idUsuario, $idAnime, $comentarioTexto);
    } else {
        $comentarioResultado = '';
    }
}

require_once "../view/templates/head.php";
require_once "../view/templates/header.php";
require_once "../view/anime.php";
include_once "../view/templates/footer.php";
