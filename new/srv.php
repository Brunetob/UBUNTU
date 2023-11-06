<?php
require_once('conf.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cedula']) && isset($_POST['check'])) {
        $cedula = $_POST['cedula'];
        $san_cedula = filter_var($cedula, FILTER_SANITIZE_NUMBER_INT);
        $sql = "SELECT name_related FROM hr_employee WHERE identification_id = :san_cedula";

        try {
            $stmt = $dbconn->prepare($sql);
            $stmt->bindParam(':san_cedula', $san_cedula);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                echo $row['name_related'];
                exit();
            } else {
                http_response_code(404);
                echo "EMPLEADO_NO_ENCONTRADO";
                exit();
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo "ERROR: " . $e->getMessage();
            exit();
        }
    }

    if (isset($_POST['cedula']) && isset($_POST['marcar'])) {
        $cedula = $_POST['cedula'];
        $san_cedula = filter_var($cedula, FILTER_SANITIZE_NUMBER_INT);
        $sql = "SELECT name_related FROM hr_employee WHERE identification_id = :san_cedula";
    
        try {
            $stmt = $dbconn->prepare($sql);
            $stmt->bindParam(':san_cedula', $san_cedula);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($row) {
                $nombre = $row['name_related'];
                $ip = strval($_SERVER['REMOTE_ADDR']);
                $equipo = strval(gethostbyaddr($_SERVER['REMOTE_ADDR']));
                $fecha = date("Y-m-d");
                $hora = date("H:i:s");
                $fecha_hora = date("Y-m-d H:i:s");
                $hora_varchar = date("H:i");
    
                $sql_insert = "INSERT INTO gpa_devicedata (usuario_cedula, usuario_name, fecha, hora, ip, fecha_hora, hora_varchar) 
                               VALUES (:san_cedula, :nombre, :fecha, :hora, :ip, :fecha_hora, :hora_varchar)";
    
                $stmt = $dbconn->prepare($sql_insert);
                $stmt->bindParam(':san_cedula', $san_cedula);
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':fecha', $fecha);
                $stmt->bindParam(':hora', $hora);
                $stmt->bindParam(':ip', $ip);
                $stmt->bindParam(':fecha_hora', $fecha_hora);
                $stmt->bindParam(':hora_varchar', $hora_varchar);
    
                if ($stmt->execute()) {
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
        } catch (PDOException $e) {
            http_response_code(500);
            echo "ERROR: " . $e->getMessage();
            exit();
        }
    }    
}
?>