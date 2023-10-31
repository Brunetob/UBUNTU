$(function(){
    $('#marcacionform').submit(function(e) {
        e.preventDefault();
        let cedula = $('#cedula').val();
        $.ajax({
            type: 'POST',
            url: 'srv.php',
            data: { cedula: cedula, marcar: true },
            success: function(data) {
                if (data.includes("ERROR")) {
                    Swal.fire({
                        'title': 'Algo salió mal.',
                        'text': 'Algo salió mal, inténtelo nuevamente.',
                        'type': 'error'
                    });
                } else if (data.includes("Empleado no encontrado")) {
                    Swal.fire({
                        'title': 'Empleado no encontrado',
                        'text': 'El empleado no existe en la base de datos.',
                        'type': 'error'
                    });
                } else {
                    Swal.fire({
                        'title': 'Marcación Exitosa',
                        'html': data,
                        'type': 'success'
                    }).then((result) => {
                        $('#cedula').val("");
                        $('#usuario').val("");
                    });
                }
            },
            error: function(data) {
                Swal.fire({
                    'title': 'Algo salió mal.',
                    'text': 'Algo salió mal, inténtelo nuevamente.',
                    'type': 'error'
                });
            }
        });
    });
    
    $("#submit").hide();
    $('#cedula').bind('input propertychange', function() {
        let d = new Date();
        let n = d.toTimeString();
        $("#submit").hide();
        $('#usuario').val("");
        let cedula = $('#cedula').val();
        if(cedula.length == 10) {
            let check = true;
            $.ajax({
                type: 'post',
                url: 'srv.php',
                data: {cedula: cedula, check: true},
                success: function(data){
                    if(data.trim() === "Funcionario no existe"){
                        $('#usuario').val("Funcionario no existe");
                        $('#hora').val("");
                        $("#submit").hide();
                    } else {
                        $('#usuario').val(data);
                        $("#submit").show();
                    }
                },
                error: function(data){
                }
            });
        }
    });
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