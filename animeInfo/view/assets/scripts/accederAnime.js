
// Función que usamos para redirigir al usuario al anime seleccionado y guardarlo en la cookie de los 5 últimos animes vistos
$(document).on('click', '.anime', function() {
    let idAnime = $(this).data('id');
    let nombreAnime = $(this).data('nombre');

    // Verificamos si las cookies han sido aceptadas para guardar el anime
    if (obtenerCookie("cookiesAceptadas") === "true") { 
        guardarUltimoAnimeVisto(idAnime, nombreAnime); // Lo almacenamos en la cookie
    }

    window.location.href = 'animeController.php?id=' + idAnime; // Redirigimos al anime
});

// Función que usamos para redirigir al usuario al anime seleccionado desde el select y guardarlo en la cookie de los 5 últimos animes vistos
$(document).on('change', '#animesRecientesVistos', function() {
    let idAnime = $(this).val(); 

    window.location.href = 'animeController.php?id=' + idAnime; // Redirigimos al anime
});

// Función para guardar el anime en las cookies solo si las cookies han sido aceptadas
function guardarUltimoAnimeVisto(idAnimeObtenido, nombreAnimeObtenido) {

    if (obtenerCookie("cookiesAceptadas") !== "true") {
        return;
    }

    let animesRecientes = obtenerCookie("ultimosAnimesVistos");

    if (animesRecientes) {
        animesRecientes = JSON.parse(animesRecientes);
    } else {
        animesRecientes = [];
    }

    // En caso de repetirse el anime se elimina y se hace un push con el actual
    animesRecientes = animesRecientes.filter(anime => anime.idAnime !== idAnimeObtenido);
    animesRecientes.push({ idAnime: idAnimeObtenido, nombreAnime: nombreAnimeObtenido });

    // Si hay más de 5 animes, eliminamos el más antiguo
    if (animesRecientes.length > 5) {
        animesRecientes.shift();
    }

    // Guardamos la cookie con los últimos animes vistos
    document.cookie = "ultimosAnimesVistos=" + JSON.stringify(animesRecientes) + "; path=/; max-age=" + 60 * 60 * 24 * 30 + "; SameSite=Lax; Secure";
}

// Función que usamos para cargar los últimos animes desde la cookie y mostrarlos en el select
function obtenerAnimesRecientes() {

    let animesRecientesVistos = obtenerCookie("ultimosAnimesVistos");

    if (animesRecientesVistos) {
        animesRecientesVistos = JSON.parse(animesRecientesVistos);
        
        let select = document.getElementById("animesRecientesVistos");

        // Creamos un option por cada anime en la cookie
        animesRecientesVistos.forEach(function(anime) {
            let option = document.createElement("option");
            option.value = anime.idAnime;
            option.classList.add("option");
            option.textContent = anime.nombreAnime;
            select.appendChild(option);
        });
    }
}

// Cargamos los animes de la cookie en el select cuando la página se cargue
$(document).ready(function() {
    obtenerAnimesRecientes();
});
