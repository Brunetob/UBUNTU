<?php
require_once('conf.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cedula']) && isset($_POST['check'])) {
        $cedula = $_POST['cedula'];
        $san_cedula = filter_var($cedula, FILTER_SANITIZE_NUMBER_INT);
        $sql = "SELECT name_related FROM hr_employee WHERE identification_id = $1";
        $result = pg_query_params($dbconn, $sql, array($san_cedula));

        if ($result) {
            $row = pg_fetch_assoc($result);
            if ($row) {
                echo $row['name_related'];
                exit();
            } else {
                http_response_code(404);
                echo "EMPLEADO_NO_ENCONTRADO";
                exit();
            }
        } else {
            http_response_code(500);
            echo "ERROR";
            exit();
        }
    }

    if (isset($_POST['cedula']) && isset($_POST['marcar'])) {
        $cedula = $_POST['cedula'];
        $san_cedula = filter_var($cedula, FILTER_SANITIZE_NUMBER_INT);
        $sql = "SELECT name_related FROM hr_employee WHERE identification_id = $1";
        $result = pg_query_params($dbconn, $sql, array($san_cedula));

        if ($result) {
            $row = pg_fetch_assoc($result);
            if ($row) {
                $nombre = $row['name_related'];
                $ip = strval($_SERVER['REMOTE_ADDR']);
                $equipo = strval(gethostbyaddr($_SERVER['REMOTE_ADDR']));
                $fecha = date("Y-m-d");
                $hora = date("H:i:s");
                $fecha_hora = date("Y-m-d H:i:s");
                $hora_varchar = date("H:i");

                $sql_insert = "INSERT INTO gpa_devicedata (usuario_cedula, usuario_name, fecha, hora, ip, fecha_hora, hora_varchar) VALUES ('$san_cedula', '$nombre', '$fecha', '$hora', '$ip', '$fecha_hora', '$hora_varchar')";

                if (pg_query($dbconn, $sql_insert)) {
                    echo "MARCACION_EXITOSA";
                    exit();
                } else {
                    http_response_code(500);
                    echo "ERROR_EN_INSERCION";
                    exit();
                }
            } else {
                http_response_code(404);
                echo "EMPLEADO_NO_ENCONTRADO";
                exit();
            }
        } else {
            http_response_code(500);
            echo "ERROR";
            exit();
        }
    }
}
?>