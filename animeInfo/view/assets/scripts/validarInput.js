// Expresiones regulares para validación

const nombreUsuarioRegex = /[A-z0-9]{5,20}/; // Entre 5 y 20 caracteres alfanumericos
const correoRegex = /^(?=.{6,30}$)[\w]+@[a-z]+\.[a-zA-Z]{2,}$/; // Correo que acabe en .com o .es y tenga entre 6 y 30 caracteres
const passwdRegex = /(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{8,20}/; // Contraseña entre 8 y 20 caracteres que tenga al menos una mayúscula, una minúscula y un número
const comentarioRegex = /(?=\S)^([A-z¿?¡!\-_0-9\s]){10,400}(?<=\S)$/ // Comentario entre 10 y 400 carácteres con alfanumericos, simbolos y que no empiece ni acabe con espacios
const listasRegex = /^(?=.*[A-z])[A-z0-9]{4,10}$/ // Lista debe tener entre 4 y 10 caracteres alfanuméricos con al menos una letra

// Función para validar los inputs con expresiones regulares

function validarInput(input, regex, elementoError, mensajeError) {

// En caso de no cumplir con la expresión regular mostramos el mensaje de error y añadimos el css de error
  if (!regex.test(input.value) && input.value) {
    elementoError.textContent = mensajeError;
    elementoError.classList.add("errorMessage");
    input.classList.add("inputError");
    return false;
  }
  if (elementoError) {
    // Si cumple la expresión regular quitamos el mensaje de error y el css
    elementoError.textContent = "";
  }
  input.classList.remove("inputError");
  return true;
}

// Función para validar la confirmación de la contraseña
function validarConfirmacion(input, inputConfirmar, elementoError, mensajeError) {

  // En caso de no cumplir con la expresión regular mostramos el mensaje de error y añadimos el css de error
  if (input.value !== inputConfirmar.value) {
    elementoError.textContent = mensajeError;
    elementoError.classList.add("errorMessage");
    inputConfirmar.classList.add("inputError");
    return false;
  }
    // Si cumple la expresión regular quitamos el mensaje de error y el css
    elementoError.textContent = "";
  inputConfirmar.classList.remove("inputError");
  return true;
}

