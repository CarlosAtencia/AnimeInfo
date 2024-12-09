// Evento para alternar el menú hamburguesa en mobile al hacer click en el elemento con id menuIcono

document.getElementById('menuIcono').addEventListener('click', function() {
    const menuIcono = document.getElementById('menuIcono');
    const navegadorMovil = document.getElementById('navegadorMovil');

    // Cambiar entre activado y no activado el menu hamburguesa

    menuIcono.classList.toggle('active');
    navegadorMovil.classList.toggle('active');
});