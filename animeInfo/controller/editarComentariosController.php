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

// Obtenemos los comentarios del usuario
$comentario = new Comentario();
$comentarioUsuario = $comentario->mostrarComentariosPorUsuario($idUsuario);

// Mostramos los comentarios por anime
$comentariosAnime = [];
foreach ($comentarioUsuario as $comentario) {
    $comentariosAnime[$comentario['idAnime']]['portada'] = $comentario['portada'];
    $comentariosAnime[$comentario['idAnime']]['nombreAnime'] = $comentario['nombreAnime'];
    $comentariosAnime[$comentario['idAnime']]['idAnime'] = $comentario['idAnime'];
    $comentariosAnime[$comentario['idAnime']]['comentarios'][] = $comentario;
}

// En caso de eliminar un comentario
if (isset($_POST['eliminarComentario'])) {
    $idComentario = $_POST['idComentario'];    
    
    $comentario = new Comentario();

    $comentario->borrarComentario($idComentario);
    header('Location: editarComentariosController.php');
    exit;
}

$css = "editarComentarios.css";
$title = "Edit Comments";

require_once "../view/templates/head.php";
require_once "../view/templates/header.php";
require_once "../view/editarComentarios.php";
include_once "../view/templates/footer.php";

?>
