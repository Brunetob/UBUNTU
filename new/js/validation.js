$(function() {
    // Cuando el formulario con id 'marcacionform' es enviado
    $('#marcacionform').submit(function(e) {
        e.preventDefault(); // Evita que el formulario se envíe de manera convencional

        // Obtiene el valor del campo 'cedula'
        let cedula = $('#cedula').val();

        // Realiza una solicitud POST a srv.php con los datos del formulario
        $.post('srv.php', { cedula: cedula, marcar: true }, function(data) {
            if (data.includes('ERROR')) {
                showErrorAlert();
            } else if (data.includes('Empleado no encontrado')) {
                $('#usuario').val('Funcionario no existe');
            } else {
                showSuccessAlert(data);
                clearFormFields();
            }
        }).fail(function() {
            showErrorAlert();
        });
    });

    $('#cedula').on('input', function() {
        // Cada vez que el campo 'cedula' cambia
        let cedula = $('#cedula').val();
        if (cedula.length === 10) {
            // Si la longitud de la cedula es igual a 10, realiza una solicitud POST a srv.php
            $.post('srv.php', { cedula: cedula, check: true }, function(data) {
                if (data.trim() === 'Funcionario no existe') {
                    $('#usuario').val('Funcionario no existe');
                } else {
                    $('#usuario').val(data);
                }
            }).fail(function() {
                console.log('Error en la solicitud');
            });
        }
    });

    // Función para mostrar un mensaje de error con SweetAlert
    function showErrorAlert() {
        Swal.fire({
            title: 'Algo salió mal.',
            text: 'Algo salió mal, inténtelo nuevamente.',
            type: 'error'
        });
    }

    // Función para mostrar un mensaje de marcación exitosa con SweetAlert
    function showSuccessAlert(data) {
        console.log(data);
        Swal.fire({
            title: 'Marcación Exitosa',
            html: data,
            type: 'success'
        }).then(() => {
            clearFormFields();
        });
    }

    // Función para borrar los valores del formulario
    function clearFormFields() {
        $('#cedula').val("");
        $('#usuario').val("");
    }

    $(document).ready(function() {
        actualizarReloj();
        setInterval(actualizarReloj, 1000);
    });
    function actualizarReloj() {
        let date = new Date(Date.now() - 7000);
        $('.digital-clock').css({'color': '#fff', 'text-shadow': '0 0 6px #ff0'});
        function addZero(x) {
            if (x < 10) {
                return x = '0' + x;
            } else {
                return x;
            }
        }
        let h = addZero(date.getHours());
        let m = addZero(date.getMinutes());
        let s = addZero(date.getSeconds());
        $('.digital-clock').text(h + ':' + m + ':' + s)
    }

    // No permitir el Enter
    $(document).on("keydown", "form", function(event) { 
        return event.key != "Enter";
    });

    $(document).ready(function () {
        // Llamado cuando se presiona una tecla en el cuadro de texto
        $("#cedula").keypress(function (e) {   
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                // Mostrar mensaje de error
                $("#errmsg").html("Solo números").show().fadeOut("slow");
                return false;
            }
        });
    });

    /*$(document).ready(function(){
        // Deshabilitar cortar, copiar y pegar en el campo de cédula
        $('#cedula').on("cut copy paste", function(e) {
            e.preventDefault();
        });
    });*/
});
