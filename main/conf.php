<?php
$serverName = "10.10.10.53,8765";
$connectionOptions = array(
    "Database" => "GADP_AZUAY",
    "Uid" => "bbravo",
    "PWD" => "pasantebb"
);

// Establecer la conexión
$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die("Error al conectar a la base de datos: " . print_r(sqlsrv_errors(), true));
} 

echo "Conexión exitosa";

// Aquí puedes realizar operaciones en la base de datos.

// Cerrar la conexión cuando hayas terminado
sqlsrv_close($conn);
?>