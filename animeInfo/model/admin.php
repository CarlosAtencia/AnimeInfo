<?php 

require_once "../autoload.php";
require_once 'funciones.inc.php';

class Admin extends Usuario {

    // Metodo del padre usado para iniciar sesion
    public function iniciarSesion(string $correo, string $passwd): string{
        $correoRegex = '/^(?=.{6,30}$)[\w]+@[a-z]+\.[a-zA-Z]{2,}$/';
        $passwdRegex = '/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{8,20}/';

        $correo = comprobarDatos($correo);
        $passwd = comprobarDatos($passwd);

        if (comprobarVacio(array($correo,$passwd))) {
            if (!preg_match($correoRegex, $correo)) {
                return "Email incorrect";
            }
    
            if (!preg_match($passwdRegex, $passwd)) {
                return "Password incorrect";
            }

            if (!self::correoEnUso($correo)) {
                return "Email not exist";
            }
    
            $conexion = AnimeInfoDB::establecerConexion();

            // Consulta para iniciar sesion con el correo
            $consulta = $conexion->prepare("SELECT * FROM usuario WHERE correo = :correo AND rol = 'admin'");
            $consulta->bindParam(':correo', $correo, PDO::PARAM_STR);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
    
            // Comprobamos si la contraseña introducida es correcta
            if ($resultado && password_verify($passwd, $resultado["passwd"])) {
                session_start();
                session_regenerate_id(true);
                $_SESSION['usuario'] = $resultado["idUsuario"];
                $_SESSION['rol'] = $resultado["rol"];
                $_SESSION['tiempoAusente'] = time();
                header('Location: ./indexController.php');
                exit();
            } else {
                return "Email or password incorrect.";
            }
        } else {
            return "Incomplete form";
        }


    }

    // Metodo para obtener clientes
    public function obtenerClientes(): array {
        $conexion = AnimeInfoDB::establecerConexion();

        // Metodo para obtener todos los clientes
        $consulta = $conexion->prepare("SELECT idUsuario, nombreUsuario, correo FROM usuario WHERE rol = 'cliente'");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    // Metodo para obtener el rol del usuario
    public function obtenerRol(string $idUsuario): string
    {
        $conexion = AnimeInfoDB::establecerConexion();
    
        // Consulta para obtener el rol del usuario
        $consulta = $conexion->prepare("SELECT rol FROM usuario WHERE idUsuario = :idUsuario");
        $consulta->bindParam(':idUsuario', $idUsuario, PDO::PARAM_STR);
        $consulta->execute();
    
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);
    
        return $usuario['rol'];
    }
}


?>