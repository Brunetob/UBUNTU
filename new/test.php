<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('conf.php'); // Incluye el archivo de configuración para la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cedula'])) {
        $cedula = $_POST['cedula']; // Obtiene la cédula de la solicitud POST
        $san_cedula = filter_var($cedula, FILTER_SANITIZE_NUMBER_INT); // Limpia y filtra la cédula

        if (isset($_POST['check'])) {
            checkEmployee($dbconn, $san_cedula);
        } elseif (isset($_POST['marcar'])) {
            markAttendance($dbconn, $san_cedula);
        }
    }
}

function checkEmployee($dbconn, $san_cedula) {
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
        handleError($e);
    }
}

function markAttendance($dbconn, $san_cedula) {
    $sql = "SELECT name_related FROM hr_employee WHERE identification_id = :san_cedula";

    try {
        $stmt = $dbconn->prepare($sql);
        $stmt->bindParam(':san_cedula', $san_cedula);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $nombre = $row['name_related'];

            $date_end = checkEmployeeStatus($dbconn, $san_cedula);

            if ($date_end === null) {
                // El usuario está activo, procede con la marcación
                $ip = strval($_SERVER['REMOTE_ADDR']);
                $equipo = strval(gethostbyaddr($_SERVER['REMOTE_ADDR']));
                $fecha = date("Y/m/d");
                $hora_varchar = $_POST['hora'];
                $hora = convertToFloat8($hora_varchar);
                $fecha_hora = date("Y-m-d H:i:s");

                insertAttendance($dbconn, $san_cedula, $nombre, $fecha, $hora, $ip, $fecha_hora, $hora_varchar);
            } else {
                http_response_code(403);
                echo "USUARIO_INACTIVO";
                exit();
            }
        } else {
            http_response_code(404);
            echo "EMPLEADO_NO_ENCONTRADO";
            exit();
        }
    } catch (PDOException $e) {
        handleError($e);
    }
}

function checkEmployeeStatus($dbconn, $san_cedula) {
    $sql = "SELECT date_end FROM hr_contract
            WHERE employee_id = (SELECT id FROM hr_employee WHERE identification_id = :san_cedula)
            AND id IN (SELECT MAX(id) FROM hr_contract WHERE employee_id = (SELECT id FROM hr_employee WHERE identification_id = :san_cedula))";

    try {
        $stmt = $dbconn->prepare($sql);
        $stmt->bindParam(':san_cedula', $san_cedula);
        $stmt->execute();
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        handleError($e);
    }
}

function insertAttendance($dbconn, $san_cedula, $nombre, $fecha, $hora, $ip, $fecha_hora, $hora_varchar) {
    $sql = "INSERT INTO gpa_devicedata (usuario_cedula, usuario_name, fecha, hora, ip, fecha_hora, hora_varchar) 
            VALUES (:san_cedula, :nombre, :fecha, :hora, :ip, :fecha_hora, :hora_varchar)";

    try {
        $stmt = $dbconn->prepare($sql);
        $stmt->bindParam(':san_cedula', $san_cedula);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':fecha_hora', $fecha_hora);
        $stmt->bindParam(':hora_varchar', $hora_varchar);

        if ($stmt->execute()) {
            getDayAttendances($dbconn, $nombre, $fecha);
        } else {
            http_response_code(500);
            echo "ERROR_EN_INSERCION: Ha ocurrido un error al insertar en la base de datos. Consulta: " . $sql;
            exit();
        }
    } catch (PDOException $e) {
        handleError($e);
    }
}

function getDayAttendances($dbconn, $nombre, $fecha) {
    $sql = "SELECT hora_marcacion FROM gpa_detalle_marcacion WHERE name = :name AND fecha_creacion = :fecha_creacion";

    try {
        $stmt = $dbconn->prepare($sql);
        $stmt->bindParam(':name', $nombre);
        $stmt->bindParam(':fecha_creacion', $fecha);
        $stmt->execute();
        $marcaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $mensaje_exitoso = "MARCACION_EXITOSA";
        if ($marcaciones) {
            $mensaje_exitoso .= "<br>Marcaciones del día: ";
            foreach ($marcaciones as $marcacion) {
                $mensaje_exitoso .= $marcacion['hora_marcacion'] . ", ";
            }
            $mensaje_exitoso = rtrim($mensaje_exitoso, ", ");
        }

        echo $mensaje_exitoso;
        exit();
    } catch (PDOException $e) {
        handleError($e);
    }
}

function convertToFloat8($hora_varchar) {
    $splitTime = explode(":", $hora_varchar);
    return $splitTime[0] + $splitTime[1] / 60 + $splitTime[2] / 3600;
}

function handleError($e) {
    http_response_code(500);
    echo "ERROR: " . $e->getMessage();
    exit();
}
