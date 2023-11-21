$(function() {
    // Cuando el formulario con id 'marcacionform' es enviado
    $('#marcacionform').submit(function(e) {
        e.preventDefault(); // Evita que el formulario se envíe de manera convencional

        // Obtiene el valor del campo 'cedula'
        let cedula = $('#cedula').val();

        // Realiza una solicitud POST a srv.php con los datos del formulario
        $.post('test.php', { cedula: cedula, marcar: true }, function(data) { // Aquí es srv.php
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

    $('#cedula').on('input', function() {
        // Cada vez que el campo 'cedula' cambia
        let cedula = $('#cedula').val();
        if (cedula.length === 0) {
            $('#usuario').val(''); // Limpiar el campo de usuario si la cédula se borra
            // Si la longitud de la cedula es igual a 10, realiza una solicitud POST a srv.php
        } else if (cedula.length === 10) {
            $.post('test.php', { cedula: cedula, check: true }, function(data) { // Aquí es srv.php
                if (data.trim() === 'EMPLEADO_NO_ENCONTRADO') {
                    $('#usuario').val('Funcionario no existexd'); // Mostrar mensaje cuando el empleado no existe
                } else {
                    $('#usuario').val(data);
                    markAttendance(cedula); // Llama a una función para registrar la asistencia con la hora actual
                }
            }).fail(function() {
                console.log('Error en la solicitud.  El funcionario no se encuentra registrado en la base de datos');
                $('#usuario').val('Funcionario no existe'); // Mostrar mensaje cuando el empleado no existe
            });
        }
    });

    //Función para obtener la hora actual
    function markAttendance(cedula) {
        // Obtiene la hora actual en formato HH:MM:SS
        let now = new Date();
        let formattedTime = now.getHours() + ':' + now.getMinutes() + ':' + now.getSeconds();
      
        $.post('test.php', { cedula: cedula, hora: formattedTime, marcar: true }, function(data) { // Aquí es srv.php
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

    // Función para mostrar un mensaje de error con SweetAlert
    function showErrorAlert(errorMsg) {
        Swal.fire({
            title: 'Algo salió mal.',
            text: 'Error: ' + errorMsg,
            type: 'error'
        });
    }

    // Función para mostrar un mensaje de marcación exitosa con SweetAlert
    function showSuccessAlert(data) {

        console.log(data); // Imprime la respuesta del servidor en la consola
        Swal.fire({//fuera del try
            title: 'Marcación Exitosa',
            html: data,
            type: 'success'
        }).then(() => {
            clearFormFields();
        });//fuera del try
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
