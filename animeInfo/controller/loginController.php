<?php

ob_start();
session_start();

require_once "../autoload.php";
require_once '../model/funciones.inc.php';

comprobarSesionIniciada(true); // Si la sesión está iniciada no le deja visualizar la página

$css = "login.css";

$title = "Login";

$fondoAleatorio = "";

$tiempoCookie = time() + 3600; // Tiempo de expiración de la cookie (1 hora)

if (isset($_COOKIE['cookiesAceptadas']) && $_COOKIE['cookiesAceptadas'] === 'true' ) {


if (!isset($_COOKIE['fondo'])) {
    // En caso de no existir la cookie creamos una
    $fondoAleatorio = rand(1, 5);
    setcookie('fondo', $fondoAleatorio, $tiempoCookie, '/');
} else {
    // En caso de existir la cookie ya existe usamos el fondo guardado en la cookie
    $fondoActual = $_COOKIE['fondo'];
    
    // Seleccionamos un fondo diferente al actual asegurado con un do while
    do {
        $nuevoFondo = rand(1, 5);
    } while ($nuevoFondo == $fondoActual);
    $fondoAleatorio = $nuevoFondo;
    
    // Actualizamos la cookie con el nuevo fondo
    // Establecer la cookie con SameSite=Lax
    setcookie('fondo', $fondoAleatorio, $tiempoCookie, '/', '', true, true);
    header('Set-Cookie: fondo=' . $fondoAleatorio . '; path=/; secure; samesite=Lax; max-age=' . $tiempoCookie);
}

}
// En caso de hacer login hacemos una peticion a la base de datos donde obtenemos el rol

// En caso de tener como rol admin se loguea como admin, sino se loguea como cliente

if (isset($_POST['login'])) {

    $correo = $_POST['correo'];
    $passwd = $_POST['passwd'];

    $conexion = AnimeInfoDB::establecerConexion();
    $consulta = $conexion->prepare("SELECT * FROM usuario WHERE correo = :correo");
    $consulta->bindParam(':correo', $correo, PDO::PARAM_STR);
    $consulta->execute();
    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
    if ($resultado) {
        $rol = $resultado['rol'];

        if ($rol === 'admin') {
            $usuario = new Admin();
            $usuario->iniciarSesion($correo, $passwd);
        } elseif ($rol === 'cliente') {
            $usuario = new Cliente();
            $usuario->iniciarSesion($correo, $passwd);
        } else {
            return "Error role.";
        }
        $resultadoFormulario = "Email or password incorrect.";
    } else {
        $resultadoFormulario = "Email or password incorrect.";
    }

} else {
    $resultadoFormulario = "";
}

require_once "../view/templates/head.php";
require_once "../view/templates/header.php";
require_once "../view/login.php";
//include_once "../view/templates/footer.php";
