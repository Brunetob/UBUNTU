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
                }
            } else {
                echo "ERROR";
            }
        }
    
        if (isset($_POST['cedula']) && isset($_POST['marcar'])) {
            $cedula = $_POST['cedula'];
            $san_cedula = filter_var($cedula, FILTER_SANITIZE_NUMBER_INT);
            $ip = strval($_SERVER['REMOTE_ADDR']);
            $equipo = strval(gethostbyaddr($_SERVER['REMOTE_ADDR']));
            $nombre = ''; // Variable para almacenar el nombre
    
            $sql = "SELECT name_related FROM hr_employee WHERE identification_id = '$san_cedula'";
            $result = pg_query($dbconn, $sql);
            if ($result) {
                while ($row = pg_fetch_assoc($result)) {
                    $nombre = $row['name_related'];
                }
            }
    
            if ($nombre !== '') {
                try {
                    $sql = "INSERT INTO gpa_devicedata (cedula, nombre, fecha, hora, ip, equipo) VALUES ('$san_cedula', '$nombre', CURRENT_DATE, CURRENT_TIME, '$ip', '$equipo')";
                    $conn->query($sql);
                } catch (Exception $e) {
                    echo "ERROR";
                }
            } else {
                echo "Empleado no encontrado"; // Si no se encuentra el empleado
            }
        }
    }
?>