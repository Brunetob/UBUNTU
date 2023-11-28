<?php
$host = "10.10.10.53";
$port = "8765";
$dbname = "GADP_AZUAY";
$user = "bbravo";
$password = "pasantebb";

$connection_string = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";

try {
    $dbconn = new PDO($connection_string);
    $dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error al conectar a la base de datos: " . $e->getMessage();
    exit();
}
//
?>