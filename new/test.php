<?php

// test.php
require_once('conf.php');
require_once('modules/dbHandler.php');
require_once('modules/employeeHandler.php');
require_once('modules/marcacionHandler.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $dbHandler = new DBHandler();
    $employeeHandler = new EmployeeHandler($dbHandler);
    $marcacionHandler = new MarcacionHandler($dbHandler);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['cedula']) && isset($_POST['check'])) {
            $cedula = $_POST['cedula'];
            $san_cedula = filter_var($cedula, FILTER_SANITIZE_NUMBER_INT);

            try {
                $result = $employeeHandler->verificarEmpleadoActivo($san_cedula);


                if ($result) {
                    echo "EMPLEADO_ACTIVO";
                    exit();
                } else {
                    http_response_code(404);
                    echo "EMPLEADO_NO_ENCONTRADO";
                    exit();
                }
            } catch (Exception $e) {
                http_response_code(500);
                echo "ERROR_VERIFICACION_EMPLEADO: " . $e->getMessage();
                exit();
            }
        } elseif (isset($_POST['cedula']) && isset($_POST['marcar'])) {
            $cedula = $_POST['cedula'];
            $san_cedula = filter_var($cedula, FILTER_SANITIZE_NUMBER_INT);

            try {
                $nombreEmpleado = $employeeHandler->getNombreEmpleado($san_cedula);
                $result = $marcacionHandler->marcarAsistencia(
                    $san_cedula,
                    $nombreEmpleado,
                    date("Y/m/d"),
                    $_POST['hora'],
                    strval($_SERVER['REMOTE_ADDR']),
                    date("Y-m-d H:i:s"),
                    $_POST['hora']
                );

                echo $result;  // Puedes devolver el mensaje de éxito o hacer algo más con el resultado
                exit();
            } catch (Exception $e) {
                http_response_code(500);
                echo "ERROR_MARCACION_ASISTENCIA: " . $e->getMessage();
                exit();
            }
        }
    }
} catch (Exception $e) {
    http_response_code(500);
    echo "ERROR_GENERAL: " . $e->getMessage();
    exit();
}
?>