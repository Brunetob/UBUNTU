<?php

// employeeHandler.php
class EmployeeHandler {
    private $dbHandler;

    public function __construct($dbHandler) {
        $this->dbHandler = $dbHandler;
    }

    public function verificarEmpleadoActivo($cedula) {
        $dbconn = $this->dbHandler->getDBConnection();
        $sql_active_check = "SELECT date_end FROM hr_contract hc
                             WHERE employee_id = (SELECT id FROM hr_employee WHERE identification_id = :cedula)
                             AND id IN (SELECT MAX(id) FROM hr_contract WHERE employee_id = (SELECT id FROM hr_employee WHERE identification_id = :cedula))";
        
        try {
            $stmt = $dbconn->prepare($sql_active_check);
            $stmt->bindParam(':cedula', $cedula);
            $stmt->execute();

            $date_end = $stmt->fetchColumn();
            return ($date_end === null); // Retorna true si el empleado está activo, false si no lo está
        } catch (PDOException $e) {
            throw new Exception("Error en la consulta de verificación de empleado activo: " . $e->getMessage());
        }
    }

    public function getNombreEmpleado($cedula) {
        $dbconn = $this->dbHandler->getDBConnection();
        $sql = "SELECT name_related FROM hr_employee WHERE identification_id = :cedula";
        
        try {
            $stmt = $dbconn->prepare($sql);
            $stmt->bindParam(':cedula', $cedula);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                return $row['name_related'];
            } else {
                throw new Exception("Empleado no encontrado");
            }
        } catch (PDOException $e) {
            throw new Exception("Error en la consulta de obtención de nombre de empleado: " . $e->getMessage());
        }
    }
}
?>
