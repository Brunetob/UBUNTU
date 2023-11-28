<?php 
// Habilita la notificación de todos los errores de PHP
error_reporting(E_ALL);
// Configura PHP para mostrar todos los errores
ini_set('display_errors', 1);

// Incluye el archivo de configuración para la base de datos
require_once('conf.php');

// Verifica si la solicitud es de tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica si la cédula fue enviada en la solicitud POST
    if (isset($_POST['cedula'])) {
        // Obtiene la cédula de la solicitud POST
        $cedula = $_POST['cedula'];
        // Limpia y filtra la cédula
        $san_cedula = filter_var($cedula, FILTER_SANITIZE_NUMBER_INT);

        // Si la solicitud POST incluye 'check', verifica la existencia del empleado
        if (isset($_POST['check'])) {
            checkEmployee($dbconn, $san_cedula);
        } 
        // Si la solicitud POST incluye 'marcar', marca la asistencia del empleado
        elseif (isset($_POST['marcar'])) {
            markAttendance($dbconn, $san_cedula);
        }
    }
}

// Esta función verifica la existencia de un empleado en la base de datos
function checkEmployee($dbconn, $san_cedula) {
    // Define la consulta SQL para buscar al empleado por su cédula
    $sql = "SELECT name_related FROM hr_employee WHERE identification_id = :san_cedula";

    try {
        // Prepara la consulta SQL
        $stmt = $dbconn->prepare($sql);
        // Vincula el parámetro ':san_cedula' a la variable '$san_cedula'
        $stmt->bindParam(':san_cedula', $san_cedula);
        // Ejecuta la consulta SQL
        $stmt->execute();
        // Obtiene la fila resultante como un array asociativo
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si se encontró una fila, es decir, si el empleado existe
        if ($row) {
            // Imprime el nombre del empleado y termina la ejecución del script
            echo $row['name_related'];
            exit();
        } else {
            // Si no se encontró una fila, es decir, si el empleado no existe
            // Establece el código de respuesta HTTP a 404 e imprime 'EMPLEADO_NO_REGISTRADO'
            http_response_code(404);
            echo "EMPLEADO_NO_REGISTRADO";
            exit();
        }
    } catch (PDOException $e) {
        // Si ocurre un error de PDO, maneja el error con la función 'handleError'
        handleError($e);
    }
}

// TODOS: Esta función marca la asistencia de un empleado en la base de datos
function markAttendance($dbconn, $san_cedula) {
    // Define la consulta SQL para buscar al empleado por su cédula
    $sql = "SELECT name_related FROM hr_employee WHERE identification_id = :san_cedula";

    try {
        $stmt = $dbconn->prepare($sql); // Prepara la consulta SQL
        $stmt->bindParam(':san_cedula', $san_cedula); // Vincula el parámetro ':san_cedula' a la variable '$san_cedula'
        $stmt->execute(); // Ejecuta la consulta SQL
        $row = $stmt->fetch(PDO::FETCH_ASSOC); // Obtiene la fila resultante como un array asociativo

        if ($row) { // Si se encontró una fila, es decir, si el empleado existe
            $nombre = $row['name_related']; // Obtiene el nombre del empleado

            $date_end = checkEmployeeStatus($dbconn, $san_cedula); // Verifica el estado del empleado

            if ($date_end === null) {
                // El usuario está activo, procede con la marcación de asistencia
                $ip = strval($_SERVER['REMOTE_ADDR']);
                $equipo = strval(gethostbyaddr($_SERVER['REMOTE_ADDR']));
                $fecha = date("Y/m/d");
                $hora_varchar = $_POST['hora'];
                $hora = convertToFloat8($hora_varchar);
                $fecha_hora = date("Y-m-d H:i:s");

                // Inserta la marcación de asistencia en la base de datos y obtiene las marcaciones del día
                insertAttendance($dbconn, $san_cedula, $nombre, $fecha, $hora, $ip, $fecha_hora, $hora_varchar); 
            } else {
                http_response_code(403); // El usuario está inactivo, establece el código de respuesta HTTP a 403
                echo "USUARIO_INACTIVO";
                exit();
            }
        } else {
            http_response_code(404); // Si no se encontró una fila, es decir, si el empleado no existe
            echo "EMPLEADO_NO_REGISTRADO";
            exit();
        }
    } catch (PDOException $e) { // Si ocurre un error de PDO, maneja el error con la función 'handleError'
        handleError($e);
    }
}

// Esta función verifica el estado de un empleado
function checkEmployeeStatus($dbconn, $san_cedula) {
    // Define la consulta SQL para obtener la fecha de finalización del contrato del empleado
    $sql = "SELECT date_end FROM hr_contract
            WHERE employee_id = (SELECT id FROM hr_employee WHERE identification_id = :san_cedula)
            AND id IN (SELECT MAX(id) FROM hr_contract WHERE employee_id = (SELECT id FROM hr_employee WHERE identification_id = :san_cedula))";

    try { // Prepara la consulta SQL
        $stmt = $dbconn->prepare($sql);
        $stmt->bindParam(':san_cedula', $san_cedula); // Vincula el parámetro ':san_cedula' a la variable '$san_cedula'
        $stmt->execute();
        return $stmt->fetchColumn(); // Obtiene la fecha de finalización del contrato del empleado
    } catch (PDOException $e) {
        handleError($e);
    }
}

// Esta función inserta la marcación de asistencia en la base de datos y obtiene las marcaciones del día
function insertAttendance($dbconn, $san_cedula, $nombre, $fecha, $hora, $ip, $fecha_hora, $hora_varchar) {
    // Define la consulta SQL para insertar la marcación de asistencia en la base de datos
    $sql = "INSERT INTO gpa_devicedata (usuario_cedula, usuario_name, fecha, hora, ip, fecha_hora, hora_varchar) 
            VALUES (:san_cedula, :nombre, :fecha, :hora, :ip, :fecha_hora, :hora_varchar)";

    try { 
        $stmt = $dbconn->prepare($sql); // Prepara la consulta SQL
        $stmt->bindParam(':san_cedula', $san_cedula); // Vincula el parámetro ':san_cedula' a la variable '$san_cedula'
        $stmt->bindParam(':nombre', $nombre); 
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':fecha_hora', $fecha_hora);
        $stmt->bindParam(':hora_varchar', $hora_varchar);

        if ($stmt->execute()) { // Ejecuta la consulta SQL
            getDayAttendances($dbconn, $nombre, $fecha); // Obtiene las marcaciones del día
        } else { // Si ocurre un error al insertar la marcación de asistencia en la base de datos
            http_response_code(500);
            echo "ERROR_EN_INSERCION: Ha ocurrido un error al insertar en la base de datos. Consulta: " . $sql;
            exit();
        }
    } catch (PDOException $e) { 
        handleError($e);
    }
}

// Esta función obtiene las marcaciones del día
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
                $hora_float = $marcacion['hora_marcacion'];
                $hours = floor($hora_float);
                $minutes = floor(($hora_float - $hours) * 60);
                $seconds = round((($hora_float - $hours) * 60 - $minutes) * 60);
                $hora_formateada = sprintf("%02d", $hours) . ":" . sprintf("%02d", $minutes) . ":" . sprintf("%02d", $seconds);
                $mensaje_exitoso .= $hora_formateada . ", ";
            }
            $mensaje_exitoso = rtrim($mensaje_exitoso, ", ");
        }

        echo $mensaje_exitoso;
        exit();
    } catch (PDOException $e) {
        handleError($e);
    }
}

/*function getDayAttendances($dbconn, $nombre, $fecha) { // 
    // Define la consulta SQL para obtener las marcaciones del día
    $sql = "SELECT hora_marcacion FROM gpa_detalle_marcacion WHERE name = :name AND fecha_creacion = :fecha_creacion";

    try {
        $stmt = $dbconn->prepare($sql); // Prepara la consulta SQL
        $stmt->bindParam(':name', $nombre); // Vincula el parámetro ':name' a la variable '$nombre'
        $stmt->bindParam(':fecha_creacion', $fecha);
        $stmt->execute();
        $marcaciones = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtiene las marcaciones del día

        $mensaje_exitoso = "MARCACION_EXITOSA"; 
        if ($marcaciones) { // Si se encontraron marcaciones del día
            $mensaje_exitoso .= "<br>Marcaciones del día: ";
            foreach ($marcaciones as $marcacion) { // Imprime las marcaciones del día
                $mensaje_exitoso .= $marcacion['hora_marcacion'] . ", "; // Concatena las marcaciones del día
            }
            $mensaje_exitoso = rtrim($mensaje_exitoso, ", ");
        }

        echo $mensaje_exitoso;
        exit();
    } catch (PDOException $e) {
        handleError($e);
    }
}*/

function convertToFloat8($hora_varchar) { // Convierte una hora en formato varchar a float8
    $splitTime = explode(":", $hora_varchar);
    return $splitTime[0] + $splitTime[1] / 60 + $splitTime[2] / 3600; 
}

function handleError($e) {
    http_response_code(500);
    echo "ERROR: " . $e->getMessage();
    exit();
}