// Comprobamos si el usuario ya ha aceptado las cookies
if (!obtenerCookie("cookiesAceptadas")) {
    mostrarCookies();
}

// Función para mostrar el cuadro de cookies en caso de no haberlas aceptado
function mostrarCookies() {
    // Generamos un fondo que bloquea el contenido que haya detrás
    let fondoCookies = document.createElement("div");
    fondoCookies.classList.add("cookie-fondoCookies");

    // Generamos el div de la cookie con el contenido
    let divCookies = document.createElement("div");
    divCookies.classList.add("cookie-divCookies");

    // Generamos el mensaje que tendrá la cookie
    let mensajeCookies = document.createElement("p");
    mensajeCookies.textContent = "This site uses cookies to enhance the user experience. By continuing to browse, you accept their use.";

    // Generamos los botones aceptar y cancelar
    let btnAceptar = document.createElement("button");
    btnAceptar.classList.add("acceptButton", "btnAcceptCancel");
    btnAceptar.textContent = "Accept";

    let btnCancelar = document.createElement("button");
    btnCancelar.classList.add("cancelButton", "btnAcceptCancel");
    btnCancelar.textContent = "Cancel";

    divCookies.appendChild(mensajeCookies);
    divCookies.appendChild(btnAceptar);
    divCookies.appendChild(btnCancelar);

    document.body.appendChild(fondoCookies);
    document.body.appendChild(divCookies);

    // Eventos cuando acepte o cancele la cookie
    btnAceptar.addEventListener("click", function() {
        document.cookie = "cookiesAceptadas=true; path=/; max-age=" + 60 * 60 * 24 * 30 + "; SameSite=Lax; Secure";
        document.body.removeChild(divCookies);
        document.body.removeChild(fondoCookies);

        location.reload();  
    });

    btnCancelar.addEventListener("click", function() {
      document.cookie = "cookiesAceptadas=false; path=/; max-age=" + 600; // 1 hora
      document.body.removeChild(divCookies);
      document.body.removeChild(fondoCookies);

      // Eliminamos las cookies en caso de tener si las cookies fueron rechazadas
      eliminarCookie("ultimosAnimesVistos");
      eliminarCookie("fondo");
      location.reload();  
    });
}

// Función para obtener la cookie insertando el nombre de este
function obtenerCookie(nombre) {
    let cookies = document.cookie.split(";");
    for (let i = 0; i < cookies.length; i++) {
        let cookie = cookies[i].trim();
        if (cookie.indexOf(nombre + "=") === 0) {
            return cookie.substring(nombre.length + 1);
        }
    }
    return "";
}

// Función para eliminar la cookie
function eliminarCookie(nombre) {
    document.cookie = nombre + "=; path=/; max-age=0";
}
