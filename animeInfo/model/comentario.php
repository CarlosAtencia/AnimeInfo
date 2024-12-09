<?php

require_once "../autoload.php";
require_once 'funciones.inc.php';

final class Comentario
{

    public function __construct(
        private string $idComentario = '',
        private string $texto = '',
        private string $fechaPublicacion = ''
    ) {}

    // Método magico get
    public function __get(string $propiedad)
    {
        return $this->$propiedad;
    }


// Método para crear un comentario
public function crearComentario(string $idUsuario, int $idAnime, string $texto): string
{
    // Limpiamos los datos
    $texto = comprobarDatos($texto);

    $comentarioRegex = '/(?=\S)^([A-z¿?¡!\-_0-9\s]){10,400}(?<=\S)$/';

    if (comprobarVacio(array($texto))) {

        if (!preg_match($comentarioRegex,$texto)) {
            return "Incorrect text format";
        }

        $conexion = AnimeInfoDB::establecerConexion();


        $idComentario = substr(uniqid(rand()), -7);  // Generamos un ID único para el comentario
    
        // Consulta para añadir el comentario a la BBDD
        $sql = "INSERT INTO comenta (idComentario, Usuario_idUsuario, Anime_idAnime, texto, fechaPublicacion) 
                  VALUES (:idComentario, :idUsuario, :idAnime, :texto, NOW())";
        $consulta = $conexion->prepare($sql);
    
        $consulta->bindParam(':idComentario', $idComentario, PDO::PARAM_STR);
        $consulta->bindParam(':idUsuario', $idUsuario, PDO::PARAM_STR);
        $consulta->bindParam(':idAnime', $idAnime, PDO::PARAM_INT);
        $consulta->bindParam(':texto', $texto, PDO::PARAM_STR);
    
        $consulta->execute();
    
        header("Location: animeController.php?id=" . $idAnime);
        exit();

    } else {
        return "Empty comment";
    }

}

    // Método para borrar un comentario
    public function borrarComentario(string $idComentario): bool
    {
        $conexion = AnimeInfoDB::establecerConexion();

        // Consulta para borrar el comentario
        $sql = "DELETE FROM comenta WHERE idComentario = :idComentario";
        $consulta = $conexion->prepare($sql);

        $consulta->bindParam(':idComentario', $idComentario, PDO::PARAM_STR);

        return $consulta->execute();
    }

    // Método para mostrar todos los comentarios de un anime
    public function mostrarComentariosPorAnime(int $idAnime): array
    {
        $conexion = AnimeInfoDB::establecerConexion();
        
        // Consulta para obtener todos los comentarios de un anime
        $sql = "SELECT c.idComentario, c.texto, c.fechaPublicacion, u.nombreUsuario, u.fotoPerfil
                FROM comenta c
                JOIN usuario u ON c.Usuario_idUsuario = u.idUsuario
                WHERE c.Anime_idAnime = :idAnime
                ORDER BY c.fechaPublicacion DESC";
        
        $consulta = $conexion->prepare($sql);
        $consulta->bindParam(':idAnime', $idAnime, PDO::PARAM_INT);
        $consulta->execute();
        
        $comentarios = $consulta->fetchAll(PDO::FETCH_ASSOC);
        
        // Insertamos en un array los comentarios hechos por el usuario con los datos necesarios
        $comentariosObtenidos = [];
        foreach ($comentarios as $comentario) {
            $fechaFormateada = date('Y-m-d', strtotime($comentario['fechaPublicacion'])); // Le ponemos el formato correcto a la fecha
            $comentariosObtenidos[] = [
                'idComentario' => $comentario['idComentario'],
                'nombreUsuario' => $comentario['nombreUsuario'],
                'texto' => $comentario['texto'],
                'fechaPublicacion' => $fechaFormateada,
                'fotoPerfil' => $comentario['fotoPerfil']
            ];
        }
        
        return $comentariosObtenidos;
    }
    
    // Método para mostrar los comentarios hechos por un usuario
    public function mostrarComentariosPorUsuario($idUsuario): array
    {
        $conexion = AnimeInfoDB::establecerConexion();

        // Consulta para obtener los comentarios hechos por un usuario
        $sql = "SELECT c.idComentario, c.texto, c.fechaPublicacion, u.nombreUsuario, a.idAnime, a.nombreAnime, a.portada
                  FROM comenta c
                  JOIN usuario u ON c.Usuario_idUsuario = u.idUsuario
                  JOIN Anime a ON c.Anime_idAnime = a.idAnime
                  WHERE c.Usuario_idUsuario = :idUsuario
                  ORDER BY c.fechaPublicacion DESC";

        $consulta = $conexion->prepare($sql);
        $consulta->bindParam(':idUsuario', $idUsuario, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}
