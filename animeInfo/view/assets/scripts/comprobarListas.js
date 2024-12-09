
// CREAR LISTA

document.addEventListener('DOMContentLoaded', () => {
    // Obtenemos el formulario, el input y el mensaje de error

    const form = document.getElementById('crearListaForm');

    const crearListaInput = document.getElementById('nombreLista');

    const crearListaError = document.getElementById('crearListaError');

    let crearListaCorrecta = false; // Variable para comprobar el estado de la validacion

    // Validamos el campo cuando el usuario escribe y cuando accede a la pagina

    crearListaInput.addEventListener('input', () => {
        crearListaCorrecta = validarInput(crearListaInput, listasRegex, crearListaError, 'Between 4 and 10 alphanumeric characters and must have at least one letter.');
    });

    crearListaCorrecta = validarInput(crearListaInput, listasRegex, crearListaError, 'Between 4 and 10 alphanumeric characters and must have at least one letter.');

    form.addEventListener('submit', (e) => {
        // Si es incorrecto deshabilitamos el boton
        if (!crearListaCorrecta) {
            e.preventDefault();
        }
    });
});

// EDITAR LISTA

document.addEventListener('DOMContentLoaded', () => {
    // Obtenemos el formulario, el input y el mensaje de error

    const form = document.getElementById('editarListaForm');

    const editarListaInput = document.getElementById('editarNombreLista');

    const editarListaError = document.getElementById('editarListaError');

    let editarListaCorrecta = false; // Variable para comprobar el estado de la validacion

    // Validamos el campo cuando el usuario escribe y cuando accede a la pagina

    editarListaInput.addEventListener('input', () => {
        editarListaCorrecta = validarInput(editarListaInput, listasRegex, editarListaError, 'Between 4 and 10 alphanumeric characters and must have at least one letter.');
    });

    editarListaCorrecta = validarInput(editarListaInput, listasRegex, editarListaError, 'Between 4 and 10 alphanumeric characters and must have at least one letter.');

    form.addEventListener('submit', (e) => {
        // Si es incorrecto deshabilitamos el boton
        if (!editarListaCorrecta) {
            e.preventDefault();
        }
    });
});