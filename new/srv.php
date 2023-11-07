<?php
require_once('conf.php'); // Incluye el archivo de configuración para la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cedula']) && isset($_POST['check'])) {
        $cedula = $_POST['cedula']; // Obtiene la cédula de la solicitud POST
        $san_cedula = filter_var($cedula, FILTER_SANITIZE_NUMBER_INT); // Limpia y filtra la cédula

        // Lógica para verificar si el empleado existe
        $sql = "SELECT name_related FROM hr_employee WHERE identification_id = :san_cedula"; // Consulta SQL

        try {
            $stmt = $dbconn->prepare($sql); // Prepara la consulta
            $stmt->bindParam(':san_cedula', $san_cedula); // Asocia el parámetro con la consulta
            $stmt->execute(); // Ejecuta la consulta
            $row = $stmt->fetch(PDO::FETCH_ASSOC); // Obtiene el resultado de la consulta como un array asociativo

            if ($row) {
                echo $row['name_related']; // Imprime el nombre del empleado asociado a la cédula
                exit();
            } else {
                http_response_code(404);  // Responde con un código de error 404 si el empleado no se encuentra
                echo "EMPLEADO_NO_ENCONTRADO";
                exit();
            }
        } catch (PDOException $e) {
            http_response_code(500); // Responde con un código de error 500 en caso de error en la consulta
            echo "ERROR: " . $e->getMessage(); // Muestra el mensaje de error
            exit();
        }
    } elseif (isset($_POST['cedula']) && isset($_POST['marcar'])) {
        $cedula = $_POST['cedula']; // Obtiene la cédula de la solicitud POST
        $san_cedula = filter_var($cedula, FILTER_SANITIZE_NUMBER_INT); // Limpia y filtra la cédula
        $sql = "SELECT name_related FROM hr_employee WHERE identification_id = :san_cedula"; // Consulta SQL

        try {
            $stmt = $dbconn->prepare($sql); // Prepara la consulta
            $stmt->bindParam(':san_cedula', $san_cedula); // Asocia el parámetro con la consulta
            $stmt->execute(); // Ejecuta la consulta
            $row = $stmt->fetch(PDO::FETCH_ASSOC); // Obtiene el resultado de la consulta como un array asociativo

            if ($row) {
                $nombre = $row['name_related']; // Obtiene el nombre del empleado
                $ip = strval($_SERVER['REMOTE_ADDR']); // Obtiene la dirección IP del cliente
                $equipo = strval(gethostbyaddr($_SERVER['REMOTE_ADDR'])); // Obtiene el nombre del equipo
                $fecha = date("Y/m/d"); // Obtiene la fecha actual
                $hora = date("H:i:s"); // Obtiene la hora actual
                $fecha_hora = date("Y-m-d H:i:s"); // Obtiene la fecha y hora actual
                $hora_varchar = date("H:i"); // Obtiene la hora actual

                $sql_insert = "INSERT INTO gpa_devicedata (usuario_cedula, usuario_name, fecha, hora, ip, fecha_hora, hora_varchar) 
                               VALUES (:san_cedula, :nombre, :fecha, :hora, :ip, :fecha_hora, :hora_varchar)"; // Consulta SQL para la inserción

                $stmt = $dbconn->prepare($sql_insert); // Prepara la consulta de inserción
                $stmt->bindParam(':san_cedula', $san_cedula); // Asocia parámetros con la consulta
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':fecha', $fecha);
                $stmt->bindParam(':hora', $hora);
                $stmt->bindParam(':ip', $ip);
                $stmt->bindParam(':fecha_hora', $fecha_hora);
                $stmt->bindParam(':hora_varchar', $hora_varchar);

                if ($stmt->execute()) { // Ejecuta la consulta de inserción
                    echo "MARCACION_EXITOSA"; // Indica que la marcación fue exitosa
                    exit();
                } else {
                    http_response_code(500); // Responde con un código de error 500 si hay un problema en la inserción
                    echo "ERROR_EN_INSERCION";
                    exit();
                }
            } else {
                http_response_code(404); // Responde con un código de error 404 si el empleado no se encuentra
                echo "EMPLEADO_NO_ENCONTRADO";
                exit();
            }
        } catch (PDOException $e) {
            http_response_code(500); // Responde con un código de error 500 en caso de error en la consulta
            echo "ERROR: " . $e->getMessage(); // Muestra el mensaje de error
            exit();
        }
    }
}
?>