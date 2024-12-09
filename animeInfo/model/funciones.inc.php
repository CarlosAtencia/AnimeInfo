<?php

// Funcion para limpiar los datos insertados
    function comprobarDatos($dato){

        $dato = trim($dato);
        $dato = stripslashes($dato);
        $dato = htmlspecialchars($dato);

        return $dato;
    }

// Funcion para comprobar si hay datos vacios

    function comprobarVacio($dato) {
        foreach ($dato as $datoArray) {
            if (empty($datoArray)) {
                return false;
            }

        }
        return true;
    }

// Funcion para comprobar si en alguna vista hay un usuario con sesion o no

    function comprobarSesionIniciada($operacion) {
        // Se usa para redireccionar a los usuarios que tengan una sesion iniciada y los que no
        if ($operacion) {
            if (isset($_SESSION['usuario'])) {
                header('Location: ./indexController.php');
                die();
            }
        } else {
            if (!isset($_SESSION['usuario'])) {
                header('Location: ./indexController.php');
                die();
            }
        }
    }

// Funcion para alternar entre el rol obtenido con el metodo

// Ademas comprobamos si se modifica el rol, en caso de ser asi, cerramos sesión

function comprobarRol($idUsuario,$rol) {
    if ($rol == "Admin") {
        $tipoRol = new Admin();
        $rolReal = $tipoRol->obtenerRol($idUsuario);
    } else {
        $tipoRol = new Cliente();
        $rolReal = $tipoRol->obtenerRol($idUsuario);
    }
    // En caso de que el rol sea erroneo
    if ($rolReal !== $rol) {
        header("Location: ./cerrarSesion.php");
    } else {
        return $tipoRol;
    }
}

// Funcion para realizar la peticion cURL para la API

    function realizarPeticionCurl($url, $query)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(['query' => $query]));

    // Ejecutamos la consulta cURL y obtener la respuesta
    $respuesta = curl_exec($curl);

    // Verificamos si hubo algún error en la ejecución de cURL
    if ($respuesta === false) {
        // Si hubo un error, mostramos el mensaje de error
        $error = curl_error($curl);
        curl_close($curl);
        throw new Exception("Error en la solicitud cURL: " . $error);
    }

    curl_close($curl);

    // Devolvemos la respuesta
    return $respuesta;
}

// Funcion para controlar la actividad de un usuario

function inactividadUsuario() {
    $tiempoMaximoInactivo = 1800; // 30 minutos maximos de inactividad

    // Si el usuario ha estado ausente más que el tiempo máximo, cuando haga alguna acción será redirigido al index y se le cierra la sesión
    if (isset($_SESSION['tiempoAusente']) && (time() - $_SESSION['tiempoAusente']) > $tiempoMaximoInactivo) {
        session_unset();
        session_destroy();
        header('Location: ./indexController.php');
        exit;
    }

    // Actualizamos el tiempo
    $_SESSION['tiempoAusente'] = time();
}




?>