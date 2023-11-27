$(function() {
    $('#marcacionform').submit(function(e) {
        e.preventDefault();
        let cedula = $('#cedula').val();
        $.post('srv.php', { cedula: cedula, marcar: true }, function(data) {
            handleResponse(data);
        }).fail(function() {
            showErrorAlert();
        });
    });

    $('#cedula').on('input', function() {
        let cedula = $('#cedula').val();
        if (cedula.length === 0) {
            $('#usuario').val('');
        } else if (cedula.length === 10) {
            $.post('srv.php', { cedula: cedula, check: true }, function(data) {
                handleCheckResponse(data);
            }).fail(function() {
                showErrorAlert();
            });
        }
    });

    function handleCheckResponse(data) {
        if (data.trim() === 'EMPLEADO_NO_REGISTRADO') {
            $('#usuario').val('Funcionario no registrado');
        } else {
            $('#usuario').val(data);
            markAttendance();
        }
    }

    function markAttendance() {
        let cedula = $('#cedula').val();
        let now = new Date();
        let formattedTime = now.getHours() + ':' + now.getMinutes() + ':' + now.getSeconds();
        $.post('srv.php', { cedula: cedula, hora: formattedTime, marcar: true }, function(data) {
            handleResponse(data);
        }).fail(function() {
            showErrorAlert();
        });
    }

    function handleResponse(data) {
        if (data.includes('ERROR')) {
            showErrorAlert();
        } else if (data.includes('EMPLEADO_NO_REGISTRADO')) {
            $('#usuario').val('Funcionario no registrado');
        } else {
            showSuccessAlert(data);
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

    /*function showErrorAlert() {
        Swal.fire({
            title: 'Algo salió mal.',
            text: 'Error en la solicitud.',
            type: 'error'
        });
    }*/
    function showErrorAlert(data) {
        switch (data) {
            case 'EMPLEADO_NO_REGISTRADO':
                Swal.fire({
                    title: 'Algo salió mal.',
                    text: 'Funcionario no registrado.',
                    type: 'error'
                });
                break;
            case 'ERROR':
                Swal.fire({
                    title: 'Algo salió mal.',
                    text: 'Error en la solicitud.',
                    type: 'error'
                });
                break;
            default:
                Swal.fire({
                    title: 'Algo salió mal.',
                    text: 'Error en la solicitud.',
                    type: 'error'
                });
                break;
        }
    }

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