/**
 * Este script maneja la validación y envío de formularios para un formulario específico.
 * Incluye funciones para manejar el envío del formulario, la validación de entradas y mostrar mensajes de éxito/error.
 * El script también incluye funciones para actualizar el reloj digital y prevenir ciertos eventos de teclado.
 *
 * @fileoverview Script de validación y envío para un formulario específico.
 * @version 1.0
 */

// FILEPATH: /C:/BRUNO/PROGRAMAS_MIOS/GITHUB_MIA/UBUNTU/new/js/validation.js

$(function() {
    /**
     * Maneja el envío del formulario cuando se envía el formulario con el id 'marcacionform'.
     * Evita que el formulario se envíe convencionalmente.
     * Obtiene el valor del campo 'cedula'.
     * Envía una solicitud POST a 'srv.php' con los datos del formulario.
     * Muestra mensajes de éxito/error según la respuesta.
     */
    $('#marcacionform').submit(function(e) {
        e.preventDefault();

        let cedula = $('#cedula').val();

        $.post('srv.php', { cedula: cedula, marcar: true }, function(data) {
            if (data.includes('ERROR')) {
                showErrorAlert();
            } else if (data.includes('EMPLEADO_NO_ENCONTRADO')) {
                $('#usuario').val('Funcionario no existe');
            } else {
                showSuccessAlert(data);
                clearFormFields();
            }
        }).fail(function() {
            showErrorAlert();
        });
    });

    /**
     * Maneja el evento de cambio de entrada para el campo 'cedula'.
     * Obtiene el valor del campo 'cedula'.
     * Si la longitud de la cedula es 0, borra el campo 'usuario'.
     * Si la longitud de la cedula es 10, envía una solicitud POST a 'srv.php' con la cedula.
     * Muestra mensajes de éxito/error según la respuesta.
     * Llama a la función 'markAttendance' para registrar la asistencia con la hora actual.
     */
    $('#cedula').on('input', function() {
        let cedula = $('#cedula').val();
        if (cedula.length === 0) {
            $('#usuario').val('');
        } else if (cedula.length === 10) {
            $.post('srv.php', { cedula: cedula, check: true }, function(data) {
                if (data.trim() === 'EMPLEADO_NO_ENCONTRADO') {
                    $('#usuario').val('Funcionario no existe');
                } else {
                    $('#usuario').val(data);
                    markAttendance(cedula);
                }
            }).fail(function() {
                console.log('Error en la solicitud. El funcionario no se encuentra registrado en la base de datos');
                $('#usuario').val('Funcionario no existe');
            });
        }
    });

    /**
     * Registra la asistencia con la hora actual.
     * Obtiene la hora actual en formato HH:MM:SS.
     * Envía una solicitud POST a 'srv.php' con la cedula y la hora formateada.
     * Muestra mensajes de éxito/error según la respuesta.
     */
    function markAttendance(cedula) {
        let now = new Date();
        let formattedTime = now.getHours() + ':' + now.getMinutes() + ':' + now.getSeconds();
        
        $.post('srv.php', { cedula: cedula, hora: formattedTime, marcar: true }, function(data) {
            if (data.trim() === 'ERROR') {
                showErrorAlert();
            } else if (data.trim() === 'EMPLEADO_NO_ENCONTRADO') {
                $('#usuario').val('Funcionario no existe');
            } else {
                showSuccessAlert(data);
                clearFormFields();
            }
        }).fail(function() {
            showErrorAlert();
        });
    }

    /**
     * Muestra un mensaje de error utilizando SweetAlert.
     * @param {string} errorMsg - El mensaje de error a mostrar.
     */
    function showErrorAlert(errorMsg) {
        Swal.fire({
            title: 'Algo salió mal.',
            text: 'Error: ' + errorMsg,
            type: 'error'
        });
    }

    /**
     * Muestra un mensaje de éxito utilizando SweetAlert.
     * Imprime la respuesta del servidor en la consola.
     * Borra los campos del formulario después de mostrar el mensaje de éxito.
     * @param {string} data - Los datos de respuesta del servidor.
     */
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

    /**
     * Borra los valores de los campos del formulario.
     */
    function clearFormFields() {
        $('#cedula').val("");
        $('#usuario').val("");
    }

    /**
     * Actualiza el reloj digital cada segundo.
     */
    $(document).ready(function() {
        actualizarReloj();
        setInterval(actualizarReloj, 1000);
    });

    /**
     * Updates the digital clock with the current time.
     */
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

    /**
     * Prevents the Enter key from being pressed inside a form.
     * @param {object} event - The keydown event object.
     * @returns {boolean} - Returns false to prevent the Enter key event.
     */
    $(document).on("keydown", "form", function(event) { 
        return event.key != "Enter";
    });

    /**
     * Validates the input to allow only numeric characters for the 'cedula' field.
     * Displays an error message if a non-numeric character is entered.
     * @param {object} e - The keypress event object.
     * @returns {boolean} - Returns false to prevent non-numeric characters from being entered.
     */
    $(document).ready(function () {
        $("#cedula").keypress(function (e) {   
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                $("#errmsg").html("Solo números").show().fadeOut("slow");
                return false;
            }
        });
    });
});
