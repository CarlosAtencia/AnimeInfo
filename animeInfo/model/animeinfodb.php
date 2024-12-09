<?php
class AnimeInfoDB {
    private static $conexion;

    public static function establecerConexion() {
        if (!isset(self::$conexion)) {
            $server = "localhost";
            $usuario = "root";
            $passwd = "root";
            $baseDatos = "animeinfo";

            // Hacemos un try catch para la conexión a la base de datos

            try {
                // Establecemos la conexión usando PDO
                $dsn = "mysql:host=$server;dbname=$baseDatos;charset=utf8";
                self::$conexion = new PDO($dsn, $usuario, $passwd);

                // Configuramos el modo de error para que use excepciones
                self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                // Captura cualquier excepción y muestra el mensaje de error
                echo "Error conectando a la base de datos: " . $e->getMessage();
                exit();
            }
        }

        return self::$conexion;
    }
}
?>