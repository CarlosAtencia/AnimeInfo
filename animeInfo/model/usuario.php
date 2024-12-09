<?php

require_once "../autoload.php";
require_once 'funciones.inc.php';

abstract class Usuario
{
    // Constructor con tipado adecuado
    public function __construct(
        private string $idUsuario = '',
        private string $nombreUsuario = '',
        private string $correo = '',
        private string $passwd = '',
        private string $fotoPerfil = '',
        private string $rol = ''
    ) {}

    // Metodo magico get
    public function __get(string $propiedad): string
    {
        return $this->$propiedad ?? '';
    }

    // Metodo para obtener un usuario con sus datos
    public function obtenerUsuario(string $idUsuario): bool
    {
        $conexion = AnimeInfoDB::establecerConexion();

        // Consulta para obtener todos los datos del usuario que tenga dicho id
        $consulta = $conexion->prepare("SELECT * FROM usuario WHERE idUsuario = :idUsuario");
        $consulta->bindParam(':idUsuario', $idUsuario, PDO::PARAM_STR);
        $consulta->execute();

        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            $this->idUsuario = $usuario['idUsuario'];
            $this->nombreUsuario = $usuario['nombreUsuario'];
            $this->correo = $usuario['correo'];
            $this->passwd = $usuario['passwd'];
            $this->fotoPerfil = base64_encode($usuario['fotoPerfil']) ?? '';
            $this->rol = $usuario['rol'];
        }

        return $usuario !== false; // Devuelve true si el usuario existe, false si no existe
    }

    // Metodo para comprobar si el nombre de usuario insertado existe
    public function nombreUsuarioEnUso(string $nombreUsuario): bool
    {
        $conexion = AnimeInfoDB::establecerConexion();

        // Consulta para comprobar si el nombre de usuario existe
        $consulta = $conexion->prepare("SELECT COUNT(*) FROM usuario WHERE nombreUsuario = :nombreUsuario");
        $consulta->bindParam(':nombreUsuario', $nombreUsuario, PDO::PARAM_STR);
        $consulta->execute();

        // En caso de dar mayor a 0 es que ya existe
        return $consulta->fetchColumn() > 0;
    }

    // Metodo para comprobar si el correo existe
    public function correoEnUso(string $correo): bool
    {
        $conexion = AnimeInfoDB::establecerConexion();

        // Consulta para comprobar si el correo existe
        $consulta = $conexion->prepare("SELECT COUNT(*) FROM usuario WHERE correo = :correo");
        $consulta->bindParam(':correo', $correo, PDO::PARAM_STR);
        $consulta->execute();

        // En caso de dar mayor a 0 es que ya existe
        return $consulta->fetchColumn() > 0;
    }

    // Metodo abstracto para obtener el rol del usuario
    abstract public function obtenerRol(string $idUsuario): string;

    // Metodo abstracto que va alternando dependiendo de si es cliente o admin
    abstract public function iniciarSesion(string $correo, string $passwd): string;

    // Metodo para editar perfil
    public function editarPerfil(string $idUsuario, string $nombreUsuario, string $correo, string $passwd, array $fotoPerfil): string {
        
        $nombreUsuarioRegex = '/[A-z0-9]{5,20}/';
        $correoRegex = '/^(?=.{6,30}$)[\w]+@[a-z]+\.[a-zA-Z]{2,}$/';
        $passwdRegex = '/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{8,20}/';
        
        // Limpiamos los datos
        $nombreUsuario = comprobarDatos($nombreUsuario);
        $correo = comprobarDatos($correo);
        $passwd = comprobarDatos($passwd);

        if (!empty($nombreUsuario)) {
            if (!preg_match($nombreUsuarioRegex, $nombreUsuario) || !is_string($nombreUsuario)) {
                return "Username incorrect.";
            }
        }

        if (!empty($correo)) {
            if (!preg_match($correoRegex, $correo) || !is_string($nombreUsuario) || !filter_var($correo, FILTER_VALIDATE_EMAIL) ) {
                return "Email incorrect.";
            }
        }

        $correo = filter_var($correo, FILTER_SANITIZE_EMAIL);


        if (!empty($passwd)) {
            if (!preg_match($passwdRegex, $passwd) || !is_string($nombreUsuario)) {
                return "Password incorrect.";
            }
        }

        $imagenSubida = isset($_FILES['img']['tmp_name']) ? $_FILES['img']['tmp_name'] : null;

        $conexion = AnimeInfoDB::establecerConexion();

        // Consulta para obtener los datos del usuario para poder actualizar los campos que cambien
        $consultaActual = $conexion->prepare("SELECT nombreUsuario, correo, passwd, fotoPerfil FROM usuario WHERE idUsuario = :idUsuario");
        $consultaActual->bindParam(':idUsuario', $idUsuario, PDO::PARAM_STR);
        $consultaActual->execute();
        $usuarioActual = $consultaActual->fetch(PDO::FETCH_ASSOC);

        // En caso de haber introducido un nombre y/o contraseña que ya exista

        if ($this->nombreUsuarioEnUso($nombreUsuario) && $nombreUsuario !== $usuarioActual['nombreUsuario']) {
            return "Username already exists.";
        }

        if ($this->correoEnUso($correo) && $correo !== $usuarioActual['correo']) {
            return "Email already exists.";
        }

        // En caso de que cambie a true se cambian los campos modificados
        $hayCambios = false;

        // Comprobamos si en cada campo hubo algun cambio

        if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {

            $imagenTipo = $_FILES['img']['type'];
            $imagenTamanio = filesize($imagenSubida);

            // Aceptamos solo JPG y PNG
            if ($imagenTipo !== "image/jpeg" && $imagenTipo !== "image/png") {
                return "Incorrect format, only admit format PNG or JPG.";
            }

            // Aceptamos como maximo 3MB de espacio de imagen
            if ($imagenTamanio >= 3145728) {
                return "The max size of a image are 3MB.";
            }

            $imagenSubida = $_FILES['img']['tmp_name'];
            $fotoPerfil = file_get_contents($imagenSubida);
            $hayCambios = true;
        } else {
            $fotoPerfil = $usuarioActual['fotoPerfil'];
        }

        if ($nombreUsuario !== $usuarioActual['nombreUsuario']) {
            $hayCambios = true;
        }

        if ($correo !== $usuarioActual['correo']) {
            $hayCambios = true;
        }

        if ($passwd !== '' && !password_verify($passwd, $usuarioActual['passwd'])) {
            $hayCambios = true;
        }

        if (empty($nombreUsuario)) {
            $nombreUsuario = $usuarioActual['nombreUsuario'];
        }
        
        if (empty($correo)) {
            $correo = $usuarioActual['correo'];
        }

        if (empty($imagenSubida)) {
            $fotoPerfil = $usuarioActual['fotoPerfil'];
        } else {
            $fotoPerfil = file_get_contents($imagenSubida);
        }

        // Si hay cambios ciframos la nueva contraseña
        if ($passwd !== '') {
            $passwdHash = password_hash($passwd, PASSWORD_DEFAULT);
        } else {
            $passwdHash = $usuarioActual['passwd'];
        }

        // En caso de hacer algun cambio ejecutamos la consulta de actualizacion
        if ($hayCambios) {
            $sql = "UPDATE usuario SET 
                        nombreUsuario = :nombreUsuario,
                        correo = :correo,
                        passwd = :passwd,
                        fotoPerfil = :fotoPerfil
                      WHERE idUsuario = :idUsuario";

            $consulta = $conexion->prepare($sql);
            $consulta->bindParam(':idUsuario', $idUsuario, PDO::PARAM_STR);
            $consulta->bindParam(':nombreUsuario', $nombreUsuario, PDO::PARAM_STR);
            $consulta->bindParam(':correo', $correo, PDO::PARAM_STR);
            $consulta->bindParam(':passwd', $passwdHash, PDO::PARAM_STR);
            $consulta->bindParam(':fotoPerfil', $fotoPerfil, PDO::PARAM_STR);
            $consulta->execute();

            // Generamos un nuevo idUsuariopara la sesion
            session_regenerate_id(true);
            header("Location: ./perfilController.php");
            exit();
        } else {
            return "Nothing changed.";
        }
    }

    // Metodo para borrar la cuenta
    public function borrarCuenta(string $idUsuario): string
    {
        $conexion = AnimeInfoDB::establecerConexion();

        // Obtenemos todos los animes a los que el usuario les ha dado "me gusta"
        $consultaMeGusta = $conexion->prepare("SELECT Anime_idAnime FROM darmegusta WHERE Usuario_idUsuario = :idUsuario");
        $consultaMeGusta->bindParam(':idUsuario', $idUsuario, PDO::PARAM_STR);
        $consultaMeGusta->execute();
        $animesMeGusta = $consultaMeGusta->fetchAll(PDO::FETCH_ASSOC);
        $consultaMeGusta->closeCursor();

        // Si hay animes que el usuario ha marcado como "me gusta", restamos el contador -1 en cada uno
        if ($animesMeGusta) {
            foreach ($animesMeGusta as $anime) {
                $idAnime = $anime['Anime_idAnime'];
                // Consulta para reducir el contador de me gusta -1 en cada anime
                $consultaRestarMeGusta = $conexion->prepare("UPDATE anime SET cantidadmegusta = cantidadmegusta - 1 WHERE idAnime = :idAnime");
                $consultaRestarMeGusta->bindParam(':idAnime', $idAnime, PDO::PARAM_STR);
                $consultaRestarMeGusta->execute();
                $consultaRestarMeGusta->closeCursor();
            }
        }

        // Consulta para borrar el usuario
        $consultaBorrarUsuario = $conexion->prepare("DELETE FROM usuario WHERE idUsuario = :idUsuario");
        $consultaBorrarUsuario->bindParam(':idUsuario', $idUsuario, PDO::PARAM_STR);
        $consultaBorrarUsuario->execute();
        $consultaBorrarUsuario->closeCursor();

        return "User deleted.";
    }
}
