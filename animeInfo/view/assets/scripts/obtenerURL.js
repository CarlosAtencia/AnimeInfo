    
    // Función para copiar la URL y cambiar el texto del botón
    document.getElementById('botonCopiarURL').addEventListener('click', function(e) {
        e.preventDefault();

        // Obtenemos la url con la API de clipboard, con writetext copiamos dicha informacion que haya dentro de este
        navigator.clipboard.writeText(window.location.href).then(function() {
            // Cambiamos el texto del botón a "URL copiada"
            document.querySelector('#botonCopiarURL span').textContent = 'URL copied';

            // Despues de unos segundos vuelve a tener el texto anterior
            setTimeout(function() {
                document.querySelector('#botonCopiarURL span').textContent = 'Copy URL';
            }, 2000);
        })
    });