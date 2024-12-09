  $(document).ready(function () {
    let cantidadAnimes = 0;
    let limiteAnimes = 20;
    let cargando = false;

    // Función para cargar más animes
    function cargarMasAnimes() {
        if (cargando) return;

        cargando = true;

        // Obtenemos los valores de los filtros
        let termino = document.getElementById("buscar").value.trim();
        let genero = document.getElementById("genero").value;
        let ordenar = document.getElementById("ordenar").value;
        let tipo = document.getElementById("tipo").value;

        $.get("ajaxVerMas.php", {
            action: "cargarAnimes",
            cantidadAnimes,
            limiteAnimes,
            buscar: termino,
            genero,
            ordenar,
            tipo,
        })
            .done(function (response) {
                let animes = JSON.parse(response);
                let resultadosAnime = document.getElementById("resultadosAnime");
                let verMasButton = document.getElementById("verMas");

                // Quitamos el mensaje de error si existe
                let errorSection = document.querySelector('section.errorSection');
                if (errorSection) {
                    errorSection.remove();
                }

                // Si no hay animes, deshabilitamos el botón y mostramos el mensaje de error solo cuando se entra a la pagina
                if (!animes || animes.length === 0) {
                    if (cantidadAnimes === 0) {
                        generarError();
                    }
                    verMasButton.disabled = true;
                    return;
                }

                // Mostramos los animes
                animes.forEach((anime) => {
                    generarAnimeDOM(anime, resultadosAnime);
                });

                // Aumentamos la cantidad de animes para poder mostrar los siguientes 20
                cantidadAnimes += limiteAnimes;

                // En caso de que los animes sean menores que el límite, deshabilitamos el botón
                if (animes.length < limiteAnimes) {
                    verMasButton.disabled = true;
                }

                cargando = false;
            })
            .fail(function () {
                console.error("Error al cargar los animes");
                cargando = false;
            });
    }

    // Función usada se cargan los primeros animes al entrar y el boton
    function cargarAnimes() {
        let verMasButton = document.getElementById("verMas");

        // Si el botón no existe, lo creamos
        if (!verMasButton) {
            verMasButton = generarVerMasButton();
        }

        // Para evitar errores por si tiene el evento de click se lo quitamos y se lo añadimos de nuevo
        verMasButton.removeEventListener("click", cargarMasAnimes);
        verMasButton.addEventListener("click", cargarMasAnimes);

        // Cargamos los primeros 20 animes
        setTimeout(function () {
            cargarMasAnimes();
        }, 500);
    }

    cargarAnimes();

    // Evento para añadir o eliminar el botón "Ver más" según la búsqueda
    let busquedaInput = document.getElementById("buscar");
    busquedaInput.addEventListener("input", function () {
        let termino = this.value;

        let verMasButton = document.getElementById("verMas");

        // En caso de que el campo de búsqueda está vacío y no hay botón "Ver más" entonces lo creamos

        // En caso de que el campo de búsqueda tenga texto y exista el boton "Ver más", lo eliminamos
        if (termino === "" && !verMasButton) {
            verMasButton = generarVerMasButton();
            verMasButton.addEventListener("click", cargarMasAnimes);
        } else if (termino !== "" && verMasButton) {
            verMasButton.remove();
        }
    });
  });

  // Función para generar el DOM de cada anime
  function generarAnimeDOM(anime, resultadosAnime) {
    let animeDiv = document.createElement("div");
    animeDiv.classList.add("anime");
    animeDiv.setAttribute("data-id", anime.id);
    animeDiv.setAttribute("data-nombre", anime.nombreAnime);

    let img = document.createElement("img");
    img.setAttribute("src", anime.portada);
    img.setAttribute("alt", anime.nombreAnime);

    let h5 = document.createElement("h5");
    h5.classList.add("my-3");
    h5.textContent = anime.nombreAnime;

    animeDiv.appendChild(img);
    animeDiv.appendChild(h5);
    resultadosAnime.appendChild(animeDiv);
  }

  // Función para generar el DOM del botón "Ver más"
  function generarVerMasButton() {
    let verMasButton = document.createElement("button");
    verMasButton.classList.add("verMas");
    verMasButton.id = "verMas";
    verMasButton.textContent = "Ver más";
    document.querySelector("section.text-center.m-4").appendChild(verMasButton);

    return verMasButton;
  }

  // Función para generar el DOM del mensaje de error
  function generarError() {
    let errorSection = document.createElement('section');
    errorSection.classList.add('errorSection', 'text-center', 'm-4');

    let p = document.createElement('p');
    p.classList.add('text');
    p.textContent = 'An error has occurred or you have exceeded the request limit. Please wait a moment before trying again.';

    errorSection.appendChild(p);

    // Insertamos el mensaje de error en el DOM, al principio de la página
    let container = document.querySelector("section.text-center.m-4");
    container.insertBefore(errorSection, container.firstChild);
  }
