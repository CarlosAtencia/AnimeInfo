
// Función que se ejecuta cuando el usuario selecciona un archivo
document.addEventListener("DOMContentLoaded", () => {
  // Seleccionamos el input y el contenedor de error
  const fileInput = document.getElementById("img");
  const imagenError = document.getElementById("imagenError");
  const imgPreview = document.getElementById("verFotoPerfil");

  // Evento para previsualizar la imagen al seleccionar un archivo
  fileInput.addEventListener("change", function (e) {
      try {
          // Obtenemos el archivo seleccionado
          const imagen = e.target.files[0];

          // Comprobamos si el archivo es una imagen
          if (imagen && imagen.type.startsWith("image/")) {
              const reader = new FileReader();

              // Función que se ejecuta cuando la imagen ha sido leída correctamente
              reader.onload = function (event) {
                  imgPreview.src = event.target.result; // Mostramos la imagen en el <img>
                  imagenError.style.display = "none"; // Ocultamos el mensaje de error si existía
              };

              reader.readAsDataURL(imagen); // Leemos el archivo como una URL en base64
          } else {
              throw new Error("Por favor, selecciona solo una imagen.");
          }
      } catch (error) {
          // Mostramos el mensaje de error
          imagenError.textContent = error.message;
          imagenError.style.display = "block";

          // Limpiamos el input y la imagen de previsualización
          fileInput.value = "";
          imgPreview.src = "";
      }
  });
});



document.addEventListener('DOMContentLoaded', () => {
    // Obtenemos el formulario
    const form = document.getElementById('formularioEditarPerfil');
    
    // Obtenemos los inputs
    const nombreUsuarioInput = document.getElementById('nombreUsuario');
    const correoInput = document.getElementById('correo');
    const passwdInput = document.getElementById('passwd');
    
    // Obtenemos los mensajes de error de los inputs
    const nombreUsuarioError = document.getElementById('nombreUsuarioError');
    const correoError = document.getElementById('correoError');
    const passwdError = document.getElementById('passwdError');
    
    // Variables para comprobar el estado de las validaciones
    let nombreUsuarioCorrecto = false;
    let correoCorrecto = false;
    let passwdCorrecto = false;

    // Validamos cada campo cuando el usuario escribe
    
    nombreUsuarioInput.addEventListener('input', () => {
        nombreUsuarioCorrecto = validarInput(nombreUsuarioInput, nombreUsuarioRegex, nombreUsuarioError, 'It must have between 5 and 30 alphanumeric characters.');
    });

    correoInput.addEventListener('input', () => {
        correoCorrecto = validarInput(correoInput, correoRegex, correoError, 'Please enter a valid email has between 6 and 30 characters.');
    });

    passwdInput.addEventListener('input', () => {
        passwdCorrecto = validarInput(passwdInput, passwdRegex, passwdError, 'The password must be between 8 and 30 characters long and include an uppercase letter, a lowercase letter, and a number.');
    });

    // Los seteamos tambien aqui en caso de estar el campo vacio o al fallar cuando seteamos el correo y contraseña que haya introducido
    nombreUsuarioCorrecto = validarInput(nombreUsuarioInput, nombreUsuarioRegex, nombreUsuarioError, 'It must have between 5 and 30 alphanumeric characters.');
    correoCorrecto = validarInput(correoInput, correoRegex, correoError, 'Please enter a valid email has between 6 and 30 characters.');
    passwdCorrecto = validarInput(passwdInput, passwdRegex, passwdError, 'The password must be between 8 and 30 characters long and include an uppercase letter, a lowercase letter, and a number.');
    
    // Prevenir envío si alguna validación es incorrecta
    form.addEventListener('submit', (e) => {
        if (!nombreUsuarioCorrecto || !correoCorrecto || !passwdCorrecto) {
            e.preventDefault();
        }
    });
});
