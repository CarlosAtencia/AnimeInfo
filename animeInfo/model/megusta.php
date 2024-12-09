<?php 

require_once "../autoload.php";
require_once 'funciones.inc.php';

interface meGusta
{
    // Método para comprobar si al usuario le gusta un anime
    public function comprobarMeGusta(int $idAnime, string $idUsuario): bool;

    // Método para dar "me gusta" a un anime
    public function darMeGusta(int $idAnime, string $idUsuario): void;

    // Método para quitar el "me gusta" de un anime
    public function quitarMeGusta(int $idAnime, string $idUsuario): void;

    // Método para mostrar los animes que le gustan a un usuario
    public function mostrarAnimesMeGusta(string $idUsuario): array;
}

?>
