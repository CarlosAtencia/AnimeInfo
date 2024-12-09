document.addEventListener('DOMContentLoaded', () => {
    // Obtenemos el formulario, los inputs y los mensajes de error

    const form = document.getElementById('formularioRegistrarse');
    
    const nombreUsuarioInput = document.getElementById('nombreUsuario');
    const correoInput = document.getElementById('correo');
    const passwdInput = document.getElementById('passwd');
    const passwdConfirmarInput = document.getElementById('passwdConfirmar');
    
    const nombreUsuarioError = document.getElementById('nombreUsuarioError');
    const correoError = document.getElementById('correoError');
    const passwdError = document.getElementById('passwdError');
    const passwdConfirmarError = document.getElementById('passwdConfirmarError');

    // Variables para comprobar el estado de las validaciones
    let nombreUsuarioCorrecto = false;
    let correoCorrecto = false;
    let passwdCorrecto = false;
    let confirmarPasswdCorrecto = false

    // Validamos cada campo cuando el usuario escribe y cuando accede a la pagina

    nombreUsuarioInput.addEventListener('input', () => {
        nombreUsuarioCorrecto = validarInput(nombreUsuarioInput, nombreUsuarioRegex, nombreUsuarioError, 'It must have between 5 and 30 alphanumeric characters.');
    });

    correoInput.addEventListener('input', () => {
        correoCorrecto = validarInput(correoInput, correoRegex, correoError, 'Please enter a valid email that has between 6 and 30 characters.');
    });

    passwdInput.addEventListener('input', () => {
        passwdCorrecto = validarInput(passwdInput, passwdRegex, passwdError, 'The password must be between 8 and 30 characters long and include an uppercase letter, a lowercase letter, and a number.');
    });

    passwdConfirmarInput.addEventListener('input', () => {
        confirmarPasswdCorrecto = validarConfirmacion(passwdInput,passwdConfirmarInput,passwdConfirmarError,"The passwords do not match.");
    });

    nombreUsuarioCorrecto = validarInput(nombreUsuarioInput, nombreUsuarioRegex, nombreUsuarioError, 'It must have between 5 and 30 alphanumeric characters.');
    correoCorrecto = validarInput(correoInput, correoRegex, correoError, 'Please enter a valid email that has between 6 and 30 characters.');
    passwdCorrecto = validarInput(passwdInput, passwdRegex, passwdError, 'The password must be between 8 and 30 characters long and include an uppercase letter, a lowercase letter, and a number.');
    confirmarPasswdCorrecto = validarConfirmacion(passwdInput,passwdConfirmarInput,passwdConfirmarError,"The passwords do not match.");


    form.addEventListener('submit', (e) => {
        // Si es incorrecto deshabilitamos el boton
        if (!nombreUsuarioCorrecto || !correoCorrecto || !passwdCorrecto || !confirmarPasswdCorrecto) {
            e.preventDefault();
        }
    });

});