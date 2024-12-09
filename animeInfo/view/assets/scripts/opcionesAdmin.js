    // Función para obtener el id del usuario a eliminar
    $(document).ready(function () {
        // Asignamos el evento de click a todos los botones con la clase "deleteUser"
        $('.deleteUser').on('click', function () {
            // Obtenemos el ID del usuario del atributo "data-id"
            const idUsuario = $(this).data('id');
    
            // Pasamos el ID al input hidden del formulario
            $('#obtenerIdUsuario').val(idUsuario);
        });
    });