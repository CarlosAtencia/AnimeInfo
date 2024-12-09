<?php

require_once "../autoload.php";
require_once 'funciones.inc.php';

final class Lista
{
    public function __construct(
        private string $idLista = '',
        private string $nombreLista = '',
        private array $anime = [],
        private string $fechaCreacion = ''
    ) {}

    // Metodo magico get
    public function __get(string $propiedad): mixed
    {
        return $this->$propiedad;
    }

    // Metodo para comprobar si el nombre de lista existe
    public function nombreListaEnUso(string $nombreLista): bool
    {
        $conexion = AnimeInfoDB::establecerConexion();

        // Consulta para comprobar si el nombre de lista existe
        $consulta = $conexion->prepare("SELECT COUNT(*) FROM lista WHERE nombreLista = :nombreLista");
        $consulta->bindParam(':nombreLista', $nombreLista, PDO::PARAM_STR);
        $consulta->execute();

        // En caso de dar mayor a 0 es que ya existe
        return $consulta->fetchColumn() > 0;
    }

    // Metodo para obtener las listas que tengan el anime que se esta mostrando
    public function obtenerListasConAnime(int $idAnime): array
    {
        $conexion = AnimeInfoDB::establecerConexion();

        // Consulta para obtener las listas que contienen el anime que estamos viendo
        $consulta = $conexion->prepare("
            SELECT DISTINCT almacena.Lista_idLista
            FROM almacena
            WHERE almacena.Anime_idAnime = :idAnime
        ");

        $consulta->bindParam(':idAnime', $idAnime, PDO::PARAM_INT);
        $consulta->execute();

        // Creamos un array para almacenar las listas que contienen el anime que se esta mostrando
        $listasConAnime = [];

        while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
            $listasConAnime[] = $fila['Lista_idLista'];
        }

        return $listasConAnime;
    }

    // Metodo estatico para añadir un anime en una lista
    public static function guardarAnimeLista(string $idLista, int $idAnime): void
    {
        $conexion = AnimeInfoDB::establecerConexion();

        // Consulta para comprobar si el anime existe en la lista
        $consultaComprobar = $conexion->prepare("SELECT 1 FROM almacena WHERE Lista_idLista = :idLista AND Anime_idAnime = :idAnime LIMIT 1");
        $consultaComprobar->bindParam(':idLista', $idLista, PDO::PARAM_STR);
        $consultaComprobar->bindParam(':idAnime', $idAnime, PDO::PARAM_INT);
        $consultaComprobar->execute();

        // Si no existe entonces lo añadimos
        if ($consultaComprobar->rowCount() == 0) {
            // Consulta para añadir el anime a la lista seleccionada
            $consulta = $conexion->prepare("INSERT INTO almacena (Lista_idLista, Anime_idAnime) VALUES (:idLista, :idAnime)");
            $consulta->bindParam(':idLista', $idLista, PDO::PARAM_STR);
            $consulta->bindParam(':idAnime', $idAnime, PDO::PARAM_INT);
            $consulta->execute();

            header("Location: animeController.php?id=" . $idAnime);
            exit;
        }
    }

    // Metodo para crear la lista
    public function crearLista(string $nombreLista): string
    {

        // Limpiamos los datos
        $nombreLista = comprobarDatos($nombreLista);

        $listaRegex = '/^(?=.*[A-z])[A-z0-9]{4,10}$/';

        // Comprobamos el nombre de lista introducido
        if (comprobarVacio(array($nombreLista))) {

            if ($this->nombreListaEnUso($nombreLista)) {
                return "The name is already in use";
            }

            if (!preg_match($listaRegex,$nombreLista) || !is_string($nombreLista)) {
                return "Incorrect name";
            }

            $conexion = AnimeInfoDB::establecerConexion();

            $idFinal = substr(uniqid(rand()), -7); // Generamos un ID único para la lista

            $fecha = date('Y-m-d'); // Obtenemos la fecha actual

            // Consulta para añadir la lista a la base de datos
            $consulta = $conexion->prepare("INSERT INTO lista (idLista, nombreLista, fechaCreacion, Usuario_idUsuario) VALUES (:idLista, :nombreLista, :fechaCreacion, :idUsuario)");
            $consulta->bindParam(':idLista', $idFinal, PDO::PARAM_STR);
            $consulta->bindParam(':nombreLista', $nombreLista, PDO::PARAM_STR);
            $consulta->bindParam(':fechaCreacion', $fecha, PDO::PARAM_STR);
            $consulta->bindParam(':idUsuario', $_SESSION["usuario"], PDO::PARAM_STR);
            $consulta->execute();

            header("Location: ./listasController.php");
            exit();
        } else {
            return "Empty name";
        }
    }

    // Metodo para obtener las listas del usuario
    public static function obtenerListasPorUsuario(string $idUsuario): array
    {
        $conexion = AnimeInfoDB::establecerConexion();

        // Consulta para obtener las listas del usuario
        $consulta = $conexion->prepare("SELECT idLista, nombreLista, fechaCreacion FROM lista WHERE Usuario_idUsuario = :idUsuario ORDER BY nombreLista ASC");
        $consulta->bindParam(':idUsuario', $idUsuario, PDO::PARAM_STR);
        $consulta->execute();

        $listas = [];
        while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
            $lista = new Lista($fila['idLista'], $fila['nombreLista'], [], $fila['fechaCreacion']);
            $listas[] = $lista;
        }

        return $listas;
    }

    // Metodo para editar la lista
    public function editarLista(string $nombreLista, string $idLista): string
    {
        // Limpiamos los datos
        $nombreLista = comprobarDatos($nombreLista);
        
        $listaRegex = '/^(?=.*[A-z])[A-z0-9]{4,10}$/';

        // Comprobamos el nuevo nombre de la lista
        if (comprobarVacio(array($nombreLista))) {

            if ($this->nombreListaEnUso($nombreLista)) {
                return "The name is already in use";
            }

            if (!preg_match($listaRegex,$nombreLista) || !is_string($nombreLista)) {
                return "Incorrect name";
            }

            $conexion = AnimeInfoDB::establecerConexion();

            // Consulta para actualizar la lista modificada en la BBDD usando el idLista
            $consulta = $conexion->prepare("UPDATE lista SET nombreLista = :nombreLista WHERE idLista = :idLista");
            $consulta->bindParam(':nombreLista', $nombreLista, PDO::PARAM_STR);
            $consulta->bindParam(':idLista', $idLista, PDO::PARAM_STR);
            $consulta->execute();

            header("Location: ./listasController.php");
            exit();
        } else {
            return "Empty name list";
        }
    }

    // Metodo para borrar la lista
    public function borrarLista(string $idLista): void
    {
        $conexion = AnimeInfoDB::establecerConexion();

        // Consulta para eliminar la lista de la BBDD
        $consulta = $conexion->prepare("DELETE FROM lista WHERE idLista = :idLista");
        $consulta->bindParam(':idLista', $idLista, PDO::PARAM_STR);
        $consulta->execute();

        header("Location: ./listasController.php");
        exit;
    }

    // Metodo para obtener el nombre de la lista, usado cuando accedemos a una insertarla como titulo en la pagina
    public function obtenerNombreLista(string $idLista): ?Lista
    {
        $conexion = AnimeInfoDB::establecerConexion();

        // Consulta para obtener el nombre de la lista en base al id ofrecido
        $consulta = $conexion->prepare("SELECT nombreLista FROM lista WHERE idLista = :idLista");
        $consulta->bindParam(':idLista', $idLista, PDO::PARAM_STR);
        $consulta->execute();

        $resultado = $consulta->fetchColumn();

        if ($resultado) {
            $lista = new Lista('', $resultado, [], '');
            return $lista;
        }

        return null;
    }

    // Metodo para eliminar un anime de la lista que estamos viendo
    public function eliminarAnimeDeLista(string $idLista, int $idAnime): void
    {
        $conexion = AnimeInfoDB::establecerConexion();

        // Consulta para eliminar el anime de la lista que estamos viendo
        $consulta = $conexion->prepare("DELETE FROM almacena WHERE Lista_idLista = :idLista AND Anime_idAnime = :idAnime");
        $consulta->bindParam(':idLista', $idLista, PDO::PARAM_STR);
        $consulta->bindParam(':idAnime', $idAnime, PDO::PARAM_INT);
        $consulta->execute();
    }
}
