$(document).ready(function () {
    let ultimaBusqueda = "";
    let ultimoGenero = "";
    let ultimoOrdenado = "";
    let ultimoTipo = "";
    let delayBusqueda = null;  // Variable para almacenar el timeout

    // Función para realizar la búsqueda
    function realizarBusqueda(cantidadAnimes = 0, limiteAnimes = 20) {
        // Obtenemos los valores insertados en los inputs
        let buscar = document.getElementById('buscar').value.trim();
        let genero = document.getElementById('genero').value;
        let ordenar = document.getElementById('ordenar').value;
        let tipo = document.getElementById('tipo').value;

        // Si la búsqueda no ha cambiado, no se hace nada
        if (buscar === ultimaBusqueda && genero === ultimoGenero && ordenar === ultimoOrdenado && tipo === ultimoTipo) {
            return;
        }

        // Realizamos la petición AJAX
        $.get('ajaxBuscador.php', {
            buscar: buscar,
            genero: genero,
            ordenar: ordenar,
            tipo: tipo,
            cantidadAnimes: cantidadAnimes,
            limiteAnimes: limiteAnimes
        })
        .done(function(response) {
            // En caso de funcionar se hae la peticion
            let animes = JSON.parse(response);
            let resultadosAnime = document.getElementById('resultadosAnime');
            
            // Limpiamos los resultados anteriores de manera segura
            while (resultadosAnime.firstChild) {
                resultadosAnime.removeChild(resultadosAnime.firstChild);
            }
            
            // Eliminamos la sección de "no resultados" en caso de existir
            let noResultadosAnime = document.querySelector('section.noResultadosAnime');
            if (noResultadosAnime) {
                noResultadosAnime.remove();
            }

            // Si obtenemos animes los muestra, sino, nos muestra un error
            if (animes.length > 0) {
                // Añadimos los animes al DOM
                animes.forEach(function(anime) {
                    generarAnimeDOM(anime, resultadosAnime);
                });
            } else {
                // Mostrar mensaje de no resultados
                generarNoResultadosDOM(resultadosAnime);
            }

            // Actualizamos las últimas búsquedas
            ultimaBusqueda = buscar;
            ultimoGenero = genero;
            ultimoOrdenado = ordenar;
            ultimoTipo = tipo;
        })
    }

    // Evento con timeout para comprobar cambios en los filtros
    $('#buscar').on('input', function () {
        clearTimeout(delayBusqueda);  // Cancelamos la búsqueda anterior si no se ejecutó
        delayBusqueda = setTimeout(function () {
            realizarBusqueda();  // Hacemos la búsqueda con un pequeño retraso
        }, 500);
    });

    // Controlamos si se realiza algun cambio, llamando asi a la funcion que realiza la busqueda
    $('#genero, #ordenar, #tipo').on('change', function () {
        clearTimeout(delayBusqueda);  // Cancelamos la búsqueda anterior si no se ejecutó
        delayBusqueda = setTimeout(function () {
            realizarBusqueda();  // Hacemos la búsqueda con un pequeño retraso
        }, 300);
    });

    // Función para generar el DOM de un anime
    function generarAnimeDOM(anime, resultadosAnime) {
        let animeDiv = document.createElement('div');
        animeDiv.classList.add('anime');
        animeDiv.setAttribute('data-id', anime.id);
        animeDiv.setAttribute('data-nombre', anime.nombreAnime);

        let img = document.createElement('img');
        img.setAttribute('src', anime.portada);
        img.setAttribute('alt', anime.nombreAnime);

        let h5 = document.createElement('h5');
        h5.classList.add('my-3');
        h5.textContent = anime.nombreAnime;

        animeDiv.appendChild(img);
        animeDiv.appendChild(h5);
        resultadosAnime.appendChild(animeDiv);
    }

    // Función para generar el DOM de resultados no encontrados
    function generarNoResultadosDOM(resultadosAnime) {
        let noResultadosAnime = document.createElement('section');
        noResultadosAnime.classList.add('noResultadosAnime', 'text-center');

        let p = document.createElement('p');
        p.classList.add('text');
        p.textContent = 'No results found.';

        noResultadosAnime.appendChild(p);
        resultadosAnime.after(noResultadosAnime);
    }
});
