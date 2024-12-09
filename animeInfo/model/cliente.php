<?php 

require_once "../autoload.php";
require_once 'funciones.inc.php';

class Cliente extends Usuario {

    // Metodo para crear la cuenta y asi poder registarse
    public function registrarse(string $nombreUsuario, string $correo, string $passwd): string {

        $nombreUsuarioRegex = '/[A-z0-9]{5,20}/';
        $correoRegex = '/^(?=.{6,30}$)[\w]+@[a-z]+\.[a-zA-Z]{2,}$/';
        $passwdRegex = '/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{8,20}/';

        // Limpiamos los datos
        $nombreUsuario = comprobarDatos($nombreUsuario);
        $correo = comprobarDatos($correo);
        $passwd = comprobarDatos($passwd);
        $confirmarPasswd = comprobarDatos($_POST['passwdConfirmar']);
    
        // Comprobaciones para validar el nombre de usuario, correo, contraseña y contraseña confirmada
        if (comprobarVacio(array($nombreUsuario, $correo, $passwd, $confirmarPasswd))) {

            if (preg_match($nombreUsuarioRegex, $nombreUsuario) == false || is_numeric($nombreUsuario)) {
                return "Username incorrect";
            }

            if (preg_match($correoRegex, $correo) == false || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                return "Email incorrect";
            }

            $correo = filter_var($correo, FILTER_SANITIZE_EMAIL);


            if (preg_match($passwdRegex, $passwd) == false) {
                return "Password incorrect";
            }
            
            if ($this->nombreUsuarioEnUso($nombreUsuario)) {
                return "Username already exists";
            }
            
            if ($this->correoEnUso($correo)) {
                return "Email already exists";
            }
    
            if ($passwd !== $confirmarPasswd) {
                return "Passwords do not match";
            }
    
            $conexion = AnimeInfoDB::establecerConexion();
    
            $passwdHash = password_hash($passwd, PASSWORD_DEFAULT); // Ciframos la contraseña

            $idFinal = substr(uniqid(rand()), -7); // Generamos un ID único para el usuario

            // Generamos una foto de perfil y rol predeterminados
            $fotoPerfilUrl = "../view/assets/images/default.jpg";
            $fotoPerfil = file_get_contents($fotoPerfilUrl);
            $rol = "cliente";
    
            // Consulta para añadir el usuario a la base de datos
            $consulta = $conexion->prepare("INSERT INTO usuario (idUsuario, nombreUsuario, correo, passwd, fotoPerfil, rol) VALUES (:idUsuario, :nombreUsuario, :correo, :passwd, :fotoPerfil, :rol)");
            $consulta->bindParam(':idUsuario', $idFinal, PDO::PARAM_STR);
            $consulta->bindParam(':nombreUsuario', $nombreUsuario, PDO::PARAM_STR);
            $consulta->bindParam(':correo', $correo, PDO::PARAM_STR);
            $consulta->bindParam(':passwd', $passwdHash, PDO::PARAM_STR);
            $consulta->bindParam(':fotoPerfil', $fotoPerfil, PDO::PARAM_STR);
            $consulta->bindParam(':rol', $rol, PDO::PARAM_STR);
            $consulta->execute();
    
            return "The account has been created successfully.";
    
        } else {
            return "Incomplete form";
        }
    }

    // Metodo del padre usado para iniciar sesion
    public function iniciarSesion(string $correo, string $passwd): string {
        $correoRegex = '/^(?=.{6,30}$)[\w]+@[a-z]+\.[a-zA-Z]{2,}$/';
        $passwdRegex = '/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{8,20}/';

        // Limpiamos los datos
        $correo = comprobarDatos($correo);
        $passwd = comprobarDatos($passwd);

        // Comprobaciones para validar el correo y contraseña
        if (comprobarVacio(array($correo, $passwd))) {
            if (!preg_match($correoRegex, $correo) || !is_string($correo)) {
                return "Email incorrect";
            }
    
            if (!preg_match($passwdRegex, $passwd) || !is_string($passwd)) {
                return "Password incorrect";
            }

            if (!self::correoEnUso($correo)) {
                return "Email not exist";
            }
    
            $conexion = AnimeInfoDB::establecerConexion();

            // Consulta para obtener los datos del usuario, necesario para iniciar sesión
            $consulta = $conexion->prepare("SELECT * FROM usuario WHERE correo = :correo AND rol = 'cliente'");
            $consulta->bindParam(':correo', $correo, PDO::PARAM_STR);
            $consulta->execute();

            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
    
            // Por ultimo comprobamos que la contraseña insertada coincida con el que tenemos en la base de datos
            if ($resultado && password_verify($passwd, $resultado["passwd"])) {
                session_start();
                // Generamos un nuevo ID para la sesion
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

    // Metodo para obtener el rol del usuario
    public function obtenerRol(string $idUsuario): string {
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
