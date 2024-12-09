<?php
require_once "../autoload.php";
require_once "../model/funciones.inc.php";

$anime = new Anime();

// Parametros que irán cambiando en base a lo que haga el usuario
$termino = $_GET['buscar'];
$genero = $_GET['genero'];
$ordenar = $_GET['ordenar'];
$tipo = $_GET['tipo'];
$cantidadAnimes = $_GET['cantidadAnimes'];
$limiteAnimes = $_GET['limiteAnimes'];

// Usamos el método obtenerAnimes de la clase Anime para obtener los datos
$animes = $anime->obtenerAnimes($cantidadAnimes, $limiteAnimes, $termino, $genero, $ordenar, $tipo);

// Devolvemos los datos como JSON
echo json_encode($animes);
exit;
?>
