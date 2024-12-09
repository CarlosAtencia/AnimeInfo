$(document).ready(function () {
    // Evento para editar el nombre de la lista
    $('.modify').on('click', function () {
        const nombreLista = $(this).data('nombre');
        const idLista = $(this).data('id');

        // Asignamos los valores a los inputs correspondientes
        $('#editarNombreLista').val(nombreLista);
        $('#idListaEditar').val(idLista);
    });

    // Evento para borrar una lista
    $('.deleteList').on('click', function () {
        const idLista = $(this).data('id');

        // Pasamos el ID al input hidden del formulario
        $('#idLista').val(idLista);
    });
});
