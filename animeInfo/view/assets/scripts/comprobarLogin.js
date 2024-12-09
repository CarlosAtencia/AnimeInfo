document.addEventListener('DOMContentLoaded', () => {
    // Obtenemos el formulario, los inputs y los mensajes de error

    const form = document.getElementById('formularioIniciarSesion');

    const correoInput = document.getElementById('correo');
    const passwdInput = document.getElementById('passwd');

    const correoError = document.getElementById('correoError');
    const passwdError = document.getElementById('passwdError');

    // Variables para comprobar el estado de las validaciones
    let correoCorrecto = false;
    let passwdCorrecto = false;

    // Validamos cada campo cuando el usuario escribe y cuando accede a la pagina

    correoInput.addEventListener('input', () => {
        correoCorrecto = validarInput(correoInput, correoRegex, correoError, 'Please enter a valid email that has between 6 and 30 characters.');
    });

    passwdInput.addEventListener('input', () => {
        passwdCorrecto = validarInput(passwdInput, passwdRegex, passwdError, 'The password must be between 8 and 30 characters long and include an uppercase letter, a lowercase letter, and a number.');
    });

    correoCorrecto = validarInput(correoInput, correoRegex, correoError, 'Please enter a valid email that has between 6 and 30 characters.');
    passwdCorrecto = validarInput(passwdInput, passwdRegex, passwdError, 'The password must be between 8 and 30 characters long and include an uppercase letter, a lowercase letter, and a number.');

    form.addEventListener('submit', (e) => {
        // Si es incorrecto deshabilitamos el boton
        if (!correoCorrecto || !passwdCorrecto) {
            e.preventDefault();
        }
    });
});