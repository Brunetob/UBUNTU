<?php
$host = "10.10.10.53";
$port = "8765"; // El puerto por defecto de PostgreSQL es 5432
$dbname = "GADP_AZUAY";
$user = "bbravo";
$password = "pasantebb";

$connection_string = "host=$host port=$port dbname=$dbname user=$user password=$password";

$dbconn = pg_connect($connection_string);

if (!$dbconn) {
    die("Error al conectar a la base de datos: ".pg_last_error());
} else{
    //echo "Conexión exitosa";
}
?>