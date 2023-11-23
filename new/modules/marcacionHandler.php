<?php

// marcacionHandler.php
class MarcacionHandler {
    private $dbHandler;

    public function __construct($dbHandler) {
        $this->dbHandler = $dbHandler;
    }

    public function marcarAsistencia($san_cedula, $nombre, $fecha, $hora, $ip, $fecha_hora, $hora_varchar) {
        $dbconn = $this->dbHandler->getDBConnection();

        // Convertir hora a float8
        $splitTime = explode(":", $hora_varchar); // Divide la hora por ":"
        $hora_float = $splitTime[0] + $splitTime[1] / 60 + $splitTime[2] / 3600; // Calcula el valor numérico decimal para float8

        $sql_insert = "INSERT INTO gpa_devicedata (usuario_cedula, usuario_name, fecha, hora, ip, fecha_hora, hora_varchar) 
                       VALUES (:san_cedula, :nombre, :fecha, :hora, :ip, :fecha_hora, :hora_varchar)";

        $stmt = $dbconn->prepare($sql_insert);
        $stmt->bindParam(':san_cedula', $san_cedula);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora_float);
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':fecha_hora', $fecha_hora);
        $stmt->bindParam(':hora_varchar', $hora_varchar);

        if ($stmt->execute()) {
            // Consulta SQL para obtener todas las marcaciones del día presente desde gpa_detalle_marcacion
            $sql_marcaciones = "SELECT hora_marcacion FROM gpa_detalle_marcacion WHERE name = :name AND fecha_creacion = :fecha_creacion";
            $stmt_marcaciones = $dbconn->prepare($sql_marcaciones);
            $stmt_marcaciones->bindParam(':name', $nombre);
            $stmt_marcaciones->bindParam(':fecha_creacion', $fecha);
            $stmt_marcaciones->execute();
            $marcaciones = $stmt_marcaciones->fetchAll(PDO::FETCH_ASSOC);

            // Formatear y devolver las marcaciones
            $mensaje_exitoso = "MARCACION_EXITOSA";
            if ($marcaciones) {
                $mensaje_exitoso .= "<br>Marcaciones del día: ";
                foreach ($marcaciones as $marcacion) {
                    $mensaje_exitoso .= $marcacion['hora_marcacion'] . ", ";
                }
                $mensaje_exitoso = rtrim($mensaje_exitoso, ", ");
            }

            return $mensaje_exitoso; // O cualquier otro valor que desees retornar en caso de éxito
        } else {
            throw new Exception("ERROR_EN_INSERCION: Ha ocurrido un error al insertar en la base de datos. Consulta: " . $sql_insert);
        }
    }
}
?>