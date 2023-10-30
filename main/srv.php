<?php
require_once('conf.php');
?>
<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['cedula']) && isset($_POST['check'])) {
            $cedula = $_POST['cedula'];
            $san_cedula = filter_var($cedula, FILTER_SANITIZE_NUMBER_INT);
            $sql = "SELECT name_related FROM hr_employee WHERE identification_id = '$san_cedula'";
            $result = pg_query($dbconn, $sql);
            if ($result) {
                while ($row = pg_fetch_assoc($result)) {
                    $nombre = $row['name_related'];
                    echo $nombre;
                    exit();
                }
            } else {
                http_response_code(500);
                echo "ERROR";
                exit();
            }
        }
    
        if (isset($_POST['cedula']) && isset($_POST['marcar'])) {
            $cedula = $_POST['cedula'];
            $san_cedula = filter_var($cedula, FILTER_SANITIZE_NUMBER_INT);
            $ip = strval($_SERVER['REMOTE_ADDR']);
            $equipo = strval(gethostbyaddr($_SERVER['REMOTE_ADDR']));
            $nombre = '';
    
            $sql = "SELECT name_related FROM hr_employee WHERE identification_id = '$san_cedula'";
            $result = pg_query($dbconn, $sql);
            if ($result) {
                while ($row = pg_fetch_assoc($result)) {
                    $nombre = $row['name_related'];
                }
            }
    
            if ($nombre !== '') {
                $sql = "INSERT INTO gpa_devicedata (cedula, nombre, fecha, hora, ip, equipo) VALUES ('$san_cedula', '$nombre', CURRENT_DATE, CURRENT_TIME, '$ip', '$equipo')";
                if ($conn->query($sql)) {
                    echo "MARCACION_EXITOSA";
                    exit();
                } else {
                    http_response_code(500);
                    echo "ERROR_EN_INSERCION";
                    exit();
                }
            } else {
                http_response_code(404);
                echo "EMPLEADO_NO_ENCONTRADO";
                exit();
            }
        }
    }
?>