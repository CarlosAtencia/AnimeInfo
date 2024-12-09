document.addEventListener('DOMContentLoaded', () => {
    // Obtenemos el formulario, el input y el mensaje de error

    const form = document.getElementById('formularioComentario');

    const comentarioInput = document.getElementById('comentario');

    const ComentarioError = document.getElementById('comentarioError');

    let comentarioCorrecto = false; // Variable para comprobar el estado de la validacion

    // Validamos el campo cuando el usuario escribe y cuando accede a la pagina

    comentarioInput.addEventListener('input', () => {
        comentarioCorrecto = validarInput(comentarioInput, comentarioRegex, ComentarioError, 'Only allow from 10 to 400 letters or numbers and this symbols : ?¿!¡-_\\');
    });

    comentarioCorrecto = validarInput(comentarioInput, comentarioRegex, ComentarioError, 'Only allow from 10 to 400 letters or numbers and this symbols : ?¿!¡-_\\');

    form.addEventListener('submit', (e) => {
        // Si es incorrecto deshabilitamos el boton
        if (!comentarioCorrecto) {
            e.preventDefault();
        }
    });
});