<?php 

// Se implementa y funciona como un autoload, llama a todas las clases aunque no se utilicen todas

    // Obtenemos la ruta completa para obtener la clase del archivo con $archivo

    // Verificamos si el archivo existe con file_exists

    // Mostramos un error con "throw new Exception" en caso de no encontrar


spl_autoload_register(function ($nombreClase) {
    $nombreClaseMinuscula = strtolower($nombreClase);

    $archivo = __DIR__ . '/model/' . $nombreClaseMinuscula . '.php';

    if (file_exists($archivo)) {
        require_once $archivo;
        return;
    }

    throw new Exception("No se pudo cargar la clase: $nombreClase");
});




?>