    <?php
        require_once "../autoload.php";
        require_once 'funciones.inc.php';


    // Interface para la gestion de los "Me gusta"


    // Clase final Anime

    final class Anime implements meGusta
    {

        // Constructor vacio por defecto
        public function __construct(
            private int $idAnime = 0,
            private string $nombreAnime = '',
            private string $tipo = '',
            private string $capitulos = '',
            private string $estado = '',
            private string $sinopsis = '',
            private string $portada = '',
            private int $cantidadMeGusta = 0,
            private string $genero = '',
            private string $fechaInicio = '',
            private ?string $fechaFin = ''
        ) {}


        // Método magicos Get

        public function __get(string $propiedad)
        {
            return $this->$propiedad;
        }

        // Método para obtener todos los generos
        public function obtenerGeneros(): ?array
        {
            $conexion = AnimeInfoDB::establecerConexion();
        
            // Consulta para obtener un máximo de 18 géneros
            $consulta = $conexion->prepare("SELECT idGenero, genero FROM Genero LIMIT 18");
            $consulta->execute();
        
            return $consulta->fetchAll(PDO::FETCH_ASSOC) ?: null;
        }
        

        // Método para comprobar si el anime ya existe en la base de datos
        public function animeExiste(int $idAnime): bool
        {
            $conexion = AnimeInfoDB::establecerConexion();

            // Consulta para obtener si existe en base a la cantidad que haya
            $consulta = $conexion->prepare("SELECT COUNT(*) FROM Anime WHERE idAnime = :idAnime");
            $consulta->bindParam(':idAnime',$idAnime,PDO::PARAM_INT);
            $consulta->execute();

            // En caso de dar mayor a 0 es que ya existe
            return $consulta->fetchColumn() > 0;
        }

        // Método para obtener animes desde la API
        public function obtenerAnimes(int $cantidadAnimes = 0, int $limiteAnimes = 20, string $termino, string $genero, string $ordenar = "ID", string $tipo): array
        {
            $apiUrl = "https://graphql.anilist.co";
            $animes = [];
        
            // Calculamos la página en base a la cantidad de animes y el límite que irá alternando
            $pagina = floor($cantidadAnimes / $limiteAnimes) + 1;
        
            // Construimos la consulta GraphQL
            // Con las variables dentro de la consulta puedo personalizar los resultados obtenidos con AJAX
            $query = "
            query {
                Page(page: $pagina, perPage: $limiteAnimes) {
                    media(type: ANIME, 
                        sort: [" . $ordenar . "] 
                        " . ($tipo ? ", format: " . strtoupper($tipo) : "") . "
                        " . ($termino ? ", search: \"$termino\"" : "") . "
                        " . ($genero ? ", genre: \"$genero\"" : "") . "
                        status_in: [FINISHED, RELEASING]
                    ) {
                        id
                        title {
                            romaji
                        }
                        coverImage {
                            large
                        }
                        status
                    }
                }
            }";
        
            // Ejecutamos la petición GraphQL usando la función que realiza la petición cURL
            $respuesta = realizarPeticionCurl($apiUrl, $query);
        
            // Obtenemos los datos en json
            $datos = json_decode($respuesta, true);

            // Comprobamos si hemos obtenido los datos
            if (isset($datos['data']['Page']['media'])) {
                foreach ($datos['data']['Page']['media'] as $animeDatos) {
                    $animes[] = [
                        'id' => $animeDatos['id'] ?? 0,
                        'nombreAnime' => $animeDatos['title']['romaji'],
                        'portada' => $animeDatos['coverImage']['large'],
                        'estado' => $animeDatos['status'],
                    ];
                }
            }
            return $animes;
        }

        // Método usado para cuando accedemos a un anime, obtenemos toda su informacion de la API al pasar el id
        public function obtenerAnimePorId(int $idAnime): array
        {
            $apiUrl = "https://graphql.anilist.co";
            $anime = [];
        
            // Consulta en graphql donde obtenemos todos los datos que vamos a insertar en la BBDD
            $query = "
            query {
                Media(id: $idAnime) {
                    id
                    title {
                        romaji
                    }
                    description
                    coverImage {
                        large
                    }
                    episodes
                    genres
                    format
                    status
                    startDate {
                        year
                        month
                        day
                    }
                    endDate {
                        year
                        month
                        day
                    }
                }
            }";
        
            // Ejecutamos la petición GraphQL usando la función que realiza la petición cURL
            $respuesta = realizarPeticionCurl($apiUrl, $query);

            // Obtenemos los datos en json
            $datos = json_decode($respuesta, true);
        
            // En caso de funcionar todo bien obtenemos el anime
            if (isset($datos['data']['Media'])) {
                $animeDatos = $datos['data']['Media'];

                // Por si alguien pone un ID de un manga en vez de un anime ponemos los formatos de anime
                $format = $animeDatos['format'];
                $tiposDeAnime = ['TV', 'TV_SHORT', 'MOVIE', 'OVA', 'ONA', 'SPECIAL', 'MUSIC'];

                if (!in_array($format, $tiposDeAnime)) {
                    header("Location: indexController.php");
                    exit();
                }

                if ($format == 'TV_SHORT') {
                    $format = 'TV Short';
                }

                if ($format == 'MUSIC') {
                    $format = 'Music';
                }

                if ($format == 'SPECIAL') {
                    $format = 'Special';
                }

                // Por si alguien quiere insertar en la URL un id de un anime distinto a FINISHED o RELEASING
                if ($animeDatos['status'] !== 'FINISHED' && $animeDatos['status'] !== "RELEASING") {
                    header("Location: indexController.php");
                    exit();
                }

                // Obtenemos la fecha de inicio
                if (isset($animeDatos['startDate'])) {
                    $anio = $animeDatos['startDate']['year'];
                    $mes = $animeDatos['startDate']['month'];
                    $dia = $animeDatos['startDate']['day'] ?? null;

                    // Irá alternando entre Año-mes-dia o Año-mes
                    $fechaInicio = $anio . '-' . $mes . ($dia ? '-' . $dia : '');
                } else {
                    $fechaInicio = null;
                }

                // Obtenemos la fecha de fin
                if (isset($animeDatos['endDate']) && isset($animeDatos['endDate']['year'])) {
                    $anio = $animeDatos['endDate']['year'];
                    $mes = $animeDatos['endDate']['month'];
                    $dia = $animeDatos['endDate']['day'];

                    $fechaFin = $anio . '-' . $mes . '-' . $dia;
                } else {
                    $fechaFin = null;
                }

                $anime = [
                    'id' => $animeDatos['id'],
                    'nombreAnime' => $animeDatos['title']['romaji'],
                    'descripcion' => $animeDatos['description'],
                    'portada' => $animeDatos['coverImage']['large'],
                    'episodios' => $animeDatos['episodes'],
                    'generos' => $animeDatos['genres'],
                    'formato' => $format,
                    'estado' => $animeDatos['status'],
                    'fechaInicio' => $fechaInicio,
                    'fechaFin' => $fechaFin
                ];
            } else {
                header("Location: indexController.php");
                exit();
            }
            return $anime;
        }


        // Método para subir un anime a la base de datos al obtener toda la informacion con el Método de obtenerAnimesPorId
        public function agregarAnime($animeDatos): void
        {
            $conexion = AnimeInfoDB::establecerConexion();
        
            $imagenBlob = file_get_contents($animeDatos['portada']); // Convertimos la portada a BLOB
        
            $sinopsisLimpia = strip_tags($animeDatos['descripcion']); // Eliminamos las etiquetas html de la sinopsis
        
            // Consulta para añadir el anime a la base de datos
            $consulta = $conexion->prepare("INSERT INTO Anime 
                (idAnime, nombreAnime, tipo, capitulos, estado, sinopsis, portada, cantidadMeGusta, fechaInicio, fechaFin) 
                VALUES (:idAnime, :nombreAnime, :tipo, :capitulos, :estado, :sinopsis, :portada, :cantidadMeGusta, :fechaInicio, :fechaFin)");
        
            $cantidadMeGusta = 0;  // Indicamos por defecto que tendrá 0 me gusta
        
            $consulta->bindParam(':idAnime', $animeDatos['id'], PDO::PARAM_INT);
            $consulta->bindParam(':nombreAnime', $animeDatos['nombreAnime'], PDO::PARAM_STR);
            $consulta->bindParam(':tipo', $animeDatos['formato'], PDO::PARAM_STR);
            $consulta->bindParam(':capitulos', $animeDatos['episodios'], PDO::PARAM_INT);
            $consulta->bindParam(':estado', $animeDatos['estado'], PDO::PARAM_STR);
            $consulta->bindParam(':sinopsis', $sinopsisLimpia, PDO::PARAM_STR);
            $consulta->bindParam(':portada', $imagenBlob, PDO::PARAM_LOB);
            $consulta->bindParam(':cantidadMeGusta', $cantidadMeGusta, PDO::PARAM_INT);
            $consulta->bindParam(':fechaInicio', $animeDatos['fechaInicio'], PDO::PARAM_STR);
            $consulta->bindParam(':fechaFin', $animeDatos['fechaFin'], PDO::PARAM_STR);
            $consulta->execute();
        
            // Comprobamos y añadimos los géneros
            $idGeneros = $animeDatos['generos'];
            $idAnime = $animeDatos['id'];
        
            foreach ($idGeneros as $genero) {
                // Consulta para comprobar si cada genero existe en la base de datos
                $consultaGenero = $conexion->prepare("SELECT idGenero FROM Genero WHERE genero = :genero");
                $consultaGenero->bindParam(':genero', $genero, PDO::PARAM_STR);
                $consultaGenero->execute();
                $resultadoGenero = $consultaGenero->fetch(PDO::FETCH_ASSOC);
        
                // Si el género ya existe entonces obtenemos su ID
                if ($resultadoGenero) {
                    $idGeneroExistente = $resultadoGenero['idGenero'];
                } else {
                    // Si el género no existe entonces lo insertamos
                    $insertarGenero = $conexion->prepare("INSERT INTO Genero (genero) VALUES (:genero)");
                    $insertarGenero->bindParam(':genero', $genero, PDO::PARAM_STR);
                    $insertarGenero->execute();
                    $idGeneroExistente = (int)$conexion->lastInsertId();
                }
        
                // Insertamos la relación entre el anime y el género en la BBDD
                $relacionarGenero = $conexion->prepare("INSERT INTO AnimeGenero (Anime_idAnime, Genero_idGenero) VALUES (:Anime_idAnime, :Genero_idGenero)");
                $relacionarGenero->bindParam(':Anime_idAnime', $idAnime, PDO::PARAM_INT);
                $relacionarGenero->bindParam(':Genero_idGenero', $idGeneroExistente, PDO::PARAM_INT);
                $relacionarGenero->execute();
            }
        }
        

        // Método para obtener los animes de la base de datos
        public function obtenerDetallesAnimeDB(int $idAnime): ?anime
        {
            $conexion = AnimeInfoDB::establecerConexion();

            // Consulta para obtener los datos del anime en la BBDD
            // Se realiza un Left Join para obtener los animes que tengan o no tengan generos
            $sql = "
                SELECT 
                    a.idAnime, 
                    a.nombreAnime, 
                    a.tipo, 
                    a.capitulos, 
                    a.estado, 
                    a.sinopsis, 
                    a.fechaInicio, 
                    a.fechaFin, 
                    a.portada, 
                    a.cantidadMeGusta,
                    COALESCE(GROUP_CONCAT(g.genero), '') AS generos
                FROM anime a
                LEFT JOIN 
                    animegenero ag ON a.idAnime = ag.Anime_idAnime
                LEFT JOIN 
                    genero g ON ag.Genero_idGenero = g.idGenero
                WHERE a.idAnime = :idAnime
                GROUP BY a.idAnime
            ";
        
            $consulta = $conexion->prepare($sql);
            $consulta->bindParam(':idAnime', $idAnime, PDO::PARAM_INT);
            $consulta->execute();
        
            $animeDatos = $consulta->fetch(PDO::FETCH_ASSOC);
        
                $portadaBase64 = base64_encode($animeDatos['portada']);  // Convertimos la portada a base64

                $generos = explode(',', $animeDatos['generos']);  // Obtenemos los generos en un array separados con una coma
        
                $anime = new Anime($animeDatos['idAnime'], $animeDatos['nombreAnime'], $animeDatos['tipo'], $animeDatos['capitulos'], $animeDatos['estado'], $animeDatos['sinopsis'], $portadaBase64, $animeDatos['cantidadMeGusta'], implode(", ", $generos),$animeDatos['fechaInicio'], $animeDatos['fechaFin'],);
            
                return $anime;
            

        }


        // Método para obtener los animes de una lista
        public function obtenerAnimesLista($idUsuario, $idLista): ?array
        {
            $conexion = AnimeInfoDB::establecerConexion();
        
            // Consulta para obtener los animes de una lista
            $sql = "
            SELECT a.idAnime, a.nombreAnime, a.portada
            FROM animeInfo.Anime a
            JOIN animeInfo.Almacena al ON a.idAnime = al.Anime_idAnime
            JOIN animeInfo.Lista l ON al.Lista_idLista = l.idLista
            JOIN animeInfo.Usuario u ON l.Usuario_idUsuario = u.idUsuario
            WHERE l.idLista = :idLista
            AND u.idUsuario = :idUsuario;
            ";
        
            $consulta = $conexion->prepare($sql);
        
            $consulta->bindParam(':idLista', $idLista, PDO::PARAM_INT);
            $consulta->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        
            $consulta->execute();
            $animeDatos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        
            if ($animeDatos) {
                $animes = [];
                foreach ($animeDatos as $dato) {
                    // Creamos un objeto insertando los datos necesarios
                    $anime = new Anime($dato['idAnime'], $dato['nombreAnime'], '', '', '', '', base64_encode($dato['portada']), 0, '','','');
                    $animes[] = $anime;
                }
                return $animes;
            }
            return null;
        }



        /* MÉTODOS DEL INTERFACE "ME GUSTA" */

        // Consulta para comprobar el usuario actual le ha dado me gusta o no al anime que está visualizando
        public function comprobarMeGusta(int $idAnime, string $idUsuario): bool
        {
            $conexion = AnimeInfoDB::establecerConexion();

            // Consulta para comprobar el usuario actual le ha dado me gusta o no al anime que está visualizando
            $sql = "SELECT COUNT(*) FROM darmegusta WHERE Anime_idAnime = :idAnime AND Usuario_idUsuario = :idUsuario";
            $consulta = $conexion->prepare($sql);
            $consulta->bindParam(':idAnime', $idAnime, PDO::PARAM_INT);
            $consulta->bindParam(':idUsuario', $idUsuario, PDO::PARAM_STR);
            $consulta->execute();
        
            return $consulta->fetchColumn() > 0;
        }

        
        // Método para dar me gusta a un anime
        public function darMeGusta(int $idAnime, string $idUsuario): void
        {
            $conexion = AnimeInfoDB::establecerConexion();
        
            // Consulta con subconsulta para añadir a me gusta e incrementar el contador de me gusta +1
            $sql = "
                INSERT INTO darmegusta (Anime_idAnime, Usuario_idUsuario)
                VALUES (:idAnime, :idUsuario);
        
                UPDATE Anime
                SET cantidadMeGusta = (
                    SELECT COUNT(*) FROM darmegusta WHERE Anime_idAnime = :idAnime
                )
                WHERE idAnime = :idAnime;
            ";
        
            $consulta = $conexion->prepare($sql);
            $consulta->bindParam(':idAnime', $idAnime, PDO::PARAM_INT);
            $consulta->bindParam(':idUsuario', $idUsuario, PDO::PARAM_STR);
            $consulta->execute();
        }
        
        // Método para quitar me gusta a un anime
        public function quitarMeGusta(int $idAnime, string $idUsuario): void
        {
            $conexion = AnimeInfoDB::establecerConexion();
        
            // Consulta con subconsulta para quitar el me gusta y reducir el contador de me gusta -1
            $sql = "
                DELETE FROM darmegusta
                WHERE Anime_idAnime = :idAnime AND Usuario_idUsuario = :idUsuario;
        
                UPDATE Anime
                SET cantidadMeGusta = (
                    SELECT COUNT(*) FROM darmegusta WHERE Anime_idAnime = :idAnime
                )
                WHERE idAnime = :idAnime;
            ";
        
            $consulta = $conexion->prepare($sql);
            $consulta->bindParam(':idAnime', $idAnime, PDO::PARAM_INT);
            $consulta->bindParam(':idUsuario', $idUsuario, PDO::PARAM_STR);
            $consulta->execute();
        }
        
        // Método para mostrar los animes que tenga me gusta el usuario
        public function mostrarAnimesMeGusta(string $idUsuario): array
        {
            $conexion = AnimeInfoDB::establecerConexion();

            // Consulta para mostrar los animes que tenga me gusta el usuario
            $sql = "SELECT a.idAnime, a.nombreAnime, a.portada 
                    FROM darmegusta m
                    JOIN Anime a ON m.Anime_idAnime = a.idAnime
                    WHERE m.Usuario_idUsuario = :idUsuario";
            
            $consulta = $conexion->prepare($sql);
            $consulta->bindParam(':idUsuario', $idUsuario, PDO::PARAM_STR);
            $consulta->execute();
            $animeDatos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        
            $animesMeGusta = [];
            if ($animeDatos) {
                foreach ($animeDatos as $anime) {
                    // Creamos un objeto Anime para cada anime de la base de datos
                    $animesMeGusta[] = new Anime($anime['idAnime'], $anime['nombreAnime'], '','','','',base64_encode($anime['portada']),0,'','','' );
                }
            }
            return $animesMeGusta;
        }

    }
