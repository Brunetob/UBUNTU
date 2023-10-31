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
        $ip = $_SERVER['REMOTE_ADDR'];
        $equipo = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $nombre = '';

        $sql = "SELECT name_related FROM hr_employee WHERE identification_id = $1";
        $result = pg_query_params($dbconn, $sql, array($san_cedula));

        if ($result) {
            $row = pg_fetch_assoc($result);
            if ($row) {
                $nombre = $row['name_related'];
                $sql = "INSERT INTO gpa_devicedata (cedula, nombre, fecha, hora, ip, equipo) VALUES ($1, $2, CURRENT_DATE, CURRENT_TIME, $3, $4)";
                $insert_result = pg_query_params($dbconn, $sql, array($san_cedula, $nombre, $ip, $equipo));

                if ($insert_result) {
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