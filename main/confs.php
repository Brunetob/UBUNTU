<?php 
try {
    $conn = new PDO("sqlsrv:Server=10.10.10.53,8765;Database=GADP_AZUAY", "bbravo","pasantebb");
    $conn->setAttribute(PDO::SQLSRV_ATTR_QUERY_TIMEOUT, 5);
    $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo 'Connected to database';
} catch(PDOException $e) {
    echo $e->getMessage();
}