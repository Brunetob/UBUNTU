<?php

// test.php
require_once('dbHandler.php');
require_once('employeeHandler.php');
require_once('marcacionHandler.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $dbHandler = new DBHandler();
    $employeeHandler = new EmployeeHandler($dbHandler);
    $marcacionHandler = new MarcacionHandler($dbHandler);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['cedula']) && isset($_POST['check'])) {
            // ... Resto del código para verificar si el empleado está activo ...
        } elseif (isset($_POST['cedula']) && isset($_POST['marcar'])) {
            // ... Resto del código para marcar la asistencia ...
        }
    }
} catch (Exception $e) {
    http_response_code(500);
    echo "ERROR: " . $e->getMessage();
    exit();
}

?>