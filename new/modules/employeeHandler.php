<?php

// employeeHandler.php
class EmployeeHandler {
    private $dbHandler;

    public function __construct($dbHandler) {
        $this->dbHandler = $dbHandler;
    }

    public function getEmployeeByName($name) {
        $dbconn = $this->dbHandler->getDBConnection();
        $sql = "SELECT name_related FROM hr_employee WHERE name_related = :name";
        
        try {
            $stmt = $dbconn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error en la consulta: " . $e->getMessage());
        }
    }
}
?>

