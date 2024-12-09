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

$css = "listas.css";
$title = "Lists";

$lista = new Lista();

$listas = $lista->obtenerListasPorUsuario($idUsuario);

// En caso de crear una lista
$resultadoFormulario = "";

if (isset($_POST['crear'])) {
    $lista = new Lista();
    $resultadoFormulario = $lista->crearLista($_POST['nombreLista']);
}

$resultado = $resultadoFormulario;

// En caso de editar una lista

if (isset($_POST['editar'])) {
        $nuevoNombre = $_POST['nombreLista'];
        $idLista = $_POST['idListaEditar'];
        
        // Realiza la edición usando el ID de la lista y el nuevo nombre
        $lista = new Lista();
        $resultadoFormulario = $lista->editarLista($nuevoNombre, $idLista);  // Pasamos el ID a la función de edición
}

$resultado = $resultadoFormulario;
       

// En caso de borrar una lista
if (isset($_POST['borrar'])) {
    if (isset($_POST['idLista']) && !empty($_POST['idLista'])) {
        $idLista = $_POST['idLista'];

        $lista = new Lista();
        $resultadoFormulario = $lista->borrarLista($idLista);
    }
}

require_once "../view/templates/head.php";
require_once "../view/templates/header.php";
require_once "../view/listas.php";
include_once "../view/templates/footer.php";
?>
