$(function() {
    // Cuando el formulario con id 'marcacionform' es enviado
    $('#marcacionform').submit(function(e) {
        e.preventDefault(); // Evita que el formulario se envíe de manera convencional

        // Obtiene el valor del campo 'cedula'
        let cedula = $('#cedula').val();

        // Realiza una solicitud AJAX
        $.ajax({
            type: 'POST', // Tipo de solicitud
            url: 'srv.php', // URL a la que se enviará la solicitud
            data: { cedula: cedula, marcar: true },  // Datos enviados en la solicitud
            success: function(data) {

                // Función ejecutada si la solicitud se completa con éxito
                if (data.includes("ERROR")) {
                    console.log(data);
                    // Mensaje de error si ocurre un problema en el servidor
                    Swal.fire({
                        title: 'Algo salió mal.',
                        text: 'Algo salió mal, inténtelo nuevamente.',
                        type: 'error'
                    });
                } else if (data.includes("Empleado no encontrado")) {
                    // Mensaje si el empleado no existe
                    Swal.fire({
                        title: 'Empleado no encontrado',
                        text: 'El empleado no existe en la base de datos.',
                        type: 'error'
                    });
                } else {
                    // Mensaje de marcación exitosa
                    console.log(data);
                    Swal.fire({
                        title: 'Marcación Exitosa',
                        html: data,
                        type: 'success'
                    }).then((result) => {
                        $('#cedula').val(""); // Borra el valor de 'cedula'
                        $('#usuario').val(""); // Borra el valor de 'usuario'
                    });
                }
            },
            error: function() {
                // Mensaje si hay un error en la solicitud AJAX
                Swal.fire({
                    title: 'Algo salió mal.',
                    text: 'Algo salió mal, inténtelo nuevamente.',
                    type: 'error'
                });
            }
        });
    });

    $('#cedula').on('input', function() {
         // Cada vez que el campo 'cedula' cambia
        let cedula = $('#cedula').val();
        if (cedula.length === 10) {
            // Si la longitud de la cedula es igual a 10, realiza una solicitud AJAX
            $.ajax({
                type: 'POST', // Tipo de solicitud
                url: 'srv.php', // URL a la que se enviará la solicitud
                data: { cedula: cedula, check: true }, // Datos enviados en la solicitud
                success: function(data) {
                    // Función ejecutada si la solicitud se completa con éxito

                    if (data.trim() === "Funcionario no existe") {
                        // Si el resultado indica que el funcionario no existe, se muestra en el campo 'usuario'
                        $('#usuario').val("Funcionario no existe");
                    } else {
                        // Muestra la respuesta en el campo 'usuario'
                        $('#usuario').val(data);
                    }
                },
                error: function() {
                    // Función ejecutada si hay un error en la solicitud AJAX
                    // Aquí se podría manejar el error
                }
            });
        }
    });
});
