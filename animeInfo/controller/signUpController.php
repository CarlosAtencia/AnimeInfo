<?php

ob_start();
session_start();

require_once "../autoload.php";
require_once '../model/funciones.inc.php';

comprobarSesionIniciada(true);

$css = "signUp.css";

$title = "Sign Up";

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
        
        setcookie('fondo', $fondoAleatorio, $tiempoCookie, '/', '', true, true);
        header('Set-Cookie: fondo=' . $fondoAleatorio . '; path=/; secure; samesite=Lax; max-age=' . $tiempoCookie);
    }
    
    }

// En caso de iniciar sesion

if (isset($_POST['signUp'])) {
    $usuario = new Cliente($_POST['correo']);
    $resultadoFormulario = $usuario->registrarse($_POST['nombreUsuario'], $_POST['correo'], $_POST['passwd']);
} else {
    $resultadoFormulario = "";
}

include "../view/templates/head.php";
include "../view/templates/header.php";
include "../view/signUp.php";
// include "../view/templates/footer.php";

