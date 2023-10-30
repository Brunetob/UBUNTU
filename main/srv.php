<?php
require_once('conf.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cedula']) && isset($_POST['check'])) {
        $cedula = $_POST['cedula'];
        $san_cedula = filter_var($cedula, FILTER_SANITIZE_NUMBER_INT);
        try {
            $sql = "SELECT name_related FROM hr_employee WHERE identification_id = '$san_cedula'";
            $result = pg_query($dbconn, $sql);
            if ($result) {
                $row = pg_fetch_assoc($result);
                if ($row) {
                    $nombre = $row['name_related'];
                    echo json_encode(['success' => true, 'nombre' => $nombre]);
                } else {
                    echo json_encode(['error' => 'Funcionario no existe']);
                }
            } else {
                echo json_encode(['error' => 'Error en la consulta']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error en la consulta']);
        }
    }

    if (isset($_POST['cedula']) && isset($_POST['marcar'])) {
        $cedula = $_POST['cedula'];
        $san_cedula = filter_var($cedula, FILTER_SANITIZE_NUMBER_INT);
        $ip = strval($_SERVER['REMOTE_ADDR']);
        $equipo = strval(gethostbyaddr($_SERVER['REMOTE_ADDR']));

        try {
            $sql = "SELECT NOMINA_ID, NOMINA_APE, NOMINA_NOM FROM NOMINA WHERE NOMINA_COD= '$san_cedula'";
            foreach ($conn->query($sql) as $row) {
                $nombre = $row['NOMINA_APE'] . " " . $row['NOMINA_NOM'];
                $id = $row['NOMINA_ID'];
                echo json_encode(['success' => true, 'nombre' => $nombre]);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error en la consulta']);
        }

        try {
            $sql = "INSERT INTO gpa_devicedata (cedula, nombre, fecha, hora, ip, equipo) VALUES ('$san_cedula', '$nombre', CURRENT_DATE, CURRENT_TIME, '$ip', '$equipo')";
            $conn->query($sql);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error en la inserción']);
        }

        // Resto del código para marcación y consultas...
    }
}
?>