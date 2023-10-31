$(function() {
    $('#marcacionform').submit(function(e) {
        e.preventDefault();
        let cedula = $('#cedula').val();
        $.ajax({
            type: 'POST',
            url: 'srv.php',
            data: { cedula: cedula, marcar: true },
            success: function(data) {
                if (data.includes("ERROR")) {
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
                    Swal.fire({
                        title: 'Marcación Exitosa',
                        html: data,
                        type: 'success'
                    }).then((result) => {
                        $('#cedula').val("");
                        $('#usuario').val("");
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
        let cedula = $('#cedula').val();
        if (cedula.length === 10) {
            $.ajax({
                type: 'POST',
                url: 'srv.php',
                data: { cedula: cedula, check: true },
                success: function(data) {
                    if (data.trim() === "Funcionario no existe") {
                        $('#usuario').val("Funcionario no existe");
                    } else {
                        $('#usuario').val(data);
                    }
                },
                error: function() {
                    // Manejar el error
                }
            });
        }
    });
});
