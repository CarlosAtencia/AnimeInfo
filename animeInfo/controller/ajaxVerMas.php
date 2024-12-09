<?php
require_once "../autoload.php";
require_once "../model/funciones.inc.php";

$anime = new Anime();

// En caso de que se pulse para ver más animes
if (isset($_GET['action']) && $_GET['action'] === 'cargarAnimes') {
    // Obtenemos la cantidad de animes en total cargados, sino por defecto empieza por 0
    $cantidadAnimes = isset($_GET['cantidadAnimes']) ? intval($_GET['cantidadAnimes']) : 0;
    $limiteAnimes = isset($_GET['limiteAnimes']) ? intval($_GET['limiteAnimes']) : 20;

    // Obtenemos los filtros enviados por el frontend
    $termino = isset($_GET['buscar']) ? $_GET['buscar'] : '';
    $genero = isset($_GET['genero']) ? $_GET['genero'] : '';
    $ordenar = isset($_GET['ordenar']) ? $_GET['ordenar'] : 'ID';
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

    // Llamamos al método obtenerAnimes de la clase Anime para obtener los resultados
    $animes = $anime->obtenerAnimes($cantidadAnimes, $limiteAnimes, $termino, $genero, $ordenar, $tipo);

    // Si no hay resultados, devolvemos un mensaje de error en formato JSON
    if (empty($animes)) {
        echo json_encode([]);
    } else {
        echo json_encode($animes);
    }
    exit;
}
?>
