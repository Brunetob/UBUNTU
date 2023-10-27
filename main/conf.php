<?php
$host = "10.10.10.53";
$port = "8765";
$dbname = "bbravo";
$user = "";
$password = "pasantebb";

$connection_string = "host=$host port=$port dbname=$dbname user=$user password=$password";

$dbconn = pg_connect($connection_string);

if (!$dbconn) {
    die("Error al conectar a la base de datos.");
} 

echo "Conexión exitosa";
pg_close($dbconn);

?>