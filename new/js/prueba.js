$(function() {
    // Selecciona el formulario con el ID 'marcacionform' y añade un controlador de eventos para el evento 'submit'
    $('#marcacionform').submit(function(e) {
        // Previene la acción por defecto del evento 'submit', que es enviar el formulario
        e.preventDefault();

        // Obtiene el valor del campo de entrada con el ID 'cedula'
        let cedula = $('#cedula').val();

        // Realiza una petición POST a 'test.php' con los datos del formulario
        // El objeto { cedula: cedula, marcar: true } se envía como cuerpo de la petición
        // 'test.php' debe ser reemplazado por 'srv.php' según el comentario existente
        $.post('test.php', { cedula: cedula, marcar: true }, function(data) {
            // Esta función se ejecuta cuando la petición es exitosa
            // 'data' contiene la respuesta del servidor
            // La función 'handleResponse' se encarga de manejar la respuesta
            handleResponse(data);
        }).fail(function(error) { //* aquí iba vacío
            // Esta función se ejecuta si la petición falla
            // La función 'showErrorAlert' se encarga de mostrar una alerta de error
            showErrorAlert(error); //* aquí iba vacío
        });
    });

    // Selecciona el campo de entrada con el ID 'cedula' y añade un controlador de eventos para el evento 'input'
    $('#cedula').on('input', function() {
        // Obtiene el valor del campo de entrada con el ID 'cedula'
        let cedula = $('#cedula').val();

        // Si el campo 'cedula' está vacío, limpia el campo 'usuario'
        if (cedula.length === 0) {
            $('#usuario').val('');
        } 
        // Si el campo 'cedula' tiene exactamente 10 caracteres
        else if (cedula.length === 10) {
            // Realiza una petición POST a 'test.php' con los datos del formulario
            // El objeto { cedula: cedula, check: true } se envía como cuerpo de la petición
            // 'test.php' debe ser reemplazado por 'srv.php' según el comentario existente
            $.post('test.php', { cedula: cedula, check: true }, function(data) {
                // Esta función se ejecuta cuando la petición es exitosa
                // 'data' contiene la respuesta del servidor
                // La función 'handleCheckResponse' se encarga de manejar la respuesta
                handleCheckResponse(data);
            }).fail(function(error) { //* aquí iba vacío
                // Esta función se ejecuta si la petición falla
                // La función 'showErrorAlert' se encarga de mostrar una alerta de error
                showErrorAlert(error); //* aquí iba vacío
            });
        }
    });

    // Esta función maneja la respuesta del servidor a la petición de verificación del empleado
    function handleCheckResponse(data) {
        // Si la respuesta del servidor es 'EMPLEADO_NO_REGISTRADO' (ignorando espacios al principio y al final)
        if (data.trim() === 'EMPLEADO_NO_REGISTRADO') {
            // Establece el valor del campo de entrada con el ID 'usuario' a 'Funcionario no registrado'
            $('#usuario').val('Funcionario no registrado');
        } else {
            // Si la respuesta del servidor es cualquier otra cosa
            // Establece el valor del campo de entrada con el ID 'usuario' a la respuesta del servidor
            $('#usuario').val(data);
            // Llama a la función 'markAttendance' para marcar la asistencia del empleado
            markAttendance();
        }
    }

    // Esta función se encarga de marcar la asistencia del empleado
    function markAttendance() {
        // Obtiene el valor del campo de entrada con el ID 'cedula'
        let cedula = $('#cedula').val();
        // Crea un nuevo objeto Date para obtener la hora actual
        let now = new Date();
        // Formatea la hora actual en el formato 'horas:minutos:segundos'
        let formattedTime = now.getHours() + ':' + now.getMinutes() + ':' + now.getSeconds();
        // Realiza una petición POST a 'test.php' con los datos del formulario
        // El objeto { cedula: cedula, hora: formattedTime, marcar: true } se envía como cuerpo de la petición
        // 'test.php' debe ser reemplazado por 'srv.php' según el comentario existente
        $.post('test.php', { cedula: cedula, hora: formattedTime, marcar: true }, function(data) {
            // Esta función se ejecuta cuando la petición es exitosa
            // 'data' contiene la respuesta del servidor
            // La función 'handleResponse' se encarga de manejar la respuesta
            handleResponse(data);
        }).fail(function(error) { //* aquí iba vacío
            // Esta función se ejecuta si la petición falla
            // La función 'showErrorAlert' se encarga de mostrar una alerta de error
            showErrorAlert(error); //* aquí iba vacío
        });
    }

    // Esta función maneja la respuesta del servidor a la petición de marcación de asistencia
    function handleResponse(data) {
        // Si la respuesta del servidor incluye la palabra 'ERROR'
        if (data.includes('ERROR')) {
            // Llama a la función 'showErrorAlert' para mostrar una alerta de error
            showErrorAlert();
        } 
        // Si la respuesta del servidor incluye la frase 'EMPLEADO_NO_REGISTRADO'
        else if (data.includes('EMPLEADO_NO_REGISTRADO')) {
            // Establece el valor del campo de entrada con el ID 'usuario' a 'Funcionario no registrado'
            $('#usuario').val('Funcionario no registrado');
        } 
        // Si la respuesta del servidor no incluye ninguna de las anteriores
        else {
            // Llama a la función 'showSuccessAlert' con la respuesta del servidor como argumento
            // para mostrar una alerta de éxito con el mensaje de la respuesta
            showSuccessAlert(data);
            // Llama a la función 'clearFormFields' para limpiar los campos del formulario
            clearFormFields();
        }
    }

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

    function showErrorAlert(error) {
        console.log(error);
        let title = 'Algo salió mal.';
        let text = 'Error en la solicitud';

        switch (error.responseText) {
            case 'EMPLEADO_NO_REGISTRADO':
                text = 'Empleado no registrado';
                break;
            case 'USUARIO_INACTIVO':
                text = 'Usuario inactivo';
                break;
            // Agrega más casos según sea necesario
            default:
                text = 'Error desconocido';
        }
    
        Swal.fire({
            title: title,
            text: text,
            type: 'error'
        });
    }

    function clearFormFields() {
        $('#cedula').val("");
        $('#usuario').val("");
    }

    $(document).ready(function() {
        actualizarReloj();
        setInterval(actualizarReloj, 1000);
    });

    /**
     * Updates the digital clock display with the current time.
     */
    function actualizarReloj() {
        let date = new Date(Date.now() - 7000);
        $('.digital-clock').css({'color': '#fff', 'text-shadow': '0 0 6px #ff0'});

        /**
         * Adds a leading zero to a number if it is less than 10.
         * @param {number} x - The number to add a leading zero to.
         * @returns {string} - The number with a leading zero if necessary.
         */
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
        $('.digital-clock').text(h + ':' + m + ':' + s);
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