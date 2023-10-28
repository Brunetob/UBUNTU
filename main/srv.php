<?php
require_once('conf.php');
?>
<?php
    if(isset($_POST['cedula']) ){//Inicio Cambios   
        if (isset($_POST['check']) && $_POST['check'] === true) {
            $cedula = $_POST['cedula'];
            $san_cedula = filter_var($cedula, FILTER_SANITIZE_NUMBER_INT);
    
            try {
                $sql = "SELECT he.name_related FROM hr_employee he WHERE he.identification_id = '$san_cedula'";
                $result = pg_query($dbconn, $sql);
    
                if ($result) {
                    while ($row = pg_fetch_assoc($result)) {
                        $nombre = $row['name_related'];
                        echo $nombre;
                    }
                } else {
                    echo "Funcionario no existe";
                }
            } catch (Exception $e) {
                echo "ERROR";
            }
        }
    
        if (isset($_POST['marcar']) && $_POST['marcar'] === true) {
            $cedula = $_POST['cedula'];
            $san_cedula = filter_var($cedula, FILTER_SANITIZE_NUMBER_INT);
            $ip = strval($_SERVER['REMOTE_ADDR']);
            $equipo = strval(gethostbyaddr($_SERVER['REMOTE_ADDR']));
    
            try {
                // Agregar lógica para recuperar los datos correspondientes de la tabla NOMINA según la cédula
                $sql = "SELECT NOMINA_ID, NOMINA_APE, NOMINA_NOM FROM NOMINA WHERE NOMINA_COD= '$san_cedula'";
                foreach ($conn->query($sql) as $row) {
                    $nombre = $row['NOMINA_APE'] . " " . $row['NOMINA_NOM'];
                    $id = $row['NOMINA_ID'];
                    print($nombre);
                }
            } catch (Exception $e) {
                print_r("ERROR");
            }
    
            try {
                // Insertar datos en la tabla ASISTNOW
                $sql = "INSERT INTO ASISTNOW (ASIS_ID, ASIS_ING, ASIS_ZONA, ASIS_FECHA, ASIS_HORA, ASIS_TIPO, ASIS_RES, ASIS_F, ASIS_FN, ASIS_HN, ASIS_PRINT, ASIS_NOVEDAD, ASIS_MAIL, ASIS_MM) SELECT '$id', GETDATE(), '170.17.0.109', CONVERT(DATE,GETDATE()), left(convert(nvarchar(20),CONVERT(TIME,GETDATE())),8),'WEB/TTRAB', 'OK', 2, CONVERT(DATE,GETDATE()), GETDATE(), 2, NULL, 1, NULL;";
                $conn->query($sql);
    
                // Lógica para actualizar registros
                $update = "UPDATE ASISTNOW SET ASIS_NOVEDAD='IP-> $ip || EQUIPO-> $equipo' WHERE ASIS_ID='$id' AND ASIS_ING=(select max(asis_ing) from asistnow where asis_id='$id');";
                $conn->query($update);
            } catch (Exception $e) {
                print_r("ERROR");
            }
        }
        if(isset($_POST['cedula']) and isset($_POST['marcar'])==true){
            $cedula	= $_POST['cedula'];
            $san_cedula = filter_var($cedula,FILTER_SANITIZE_NUMBER_INT);    
            $ip=strval($_SERVER['REMOTE_ADDR']);
            $equipo=strval(gethostbyaddr($_SERVER['REMOTE_ADDR']));

            try {
                //consulta marcacion y muestra
                $sql = "SELECT NOMINA_ID, NOMINA_APE, NOMINA_NOM FROM NOMINA WHERE NOMINA_COD= '$san_cedula'";
                foreach ($conn->query($sql) as $row){
                $nombre = $row['NOMINA_APE']." ".$row['NOMINA_NOM'] ;
                $id=$row['NOMINA_ID']; 
                print($nombre);              
                        
                }
            } catch (Exception $e) {
               print_r("ERROR");
               //echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            }
            
            try {
                $sql = "INSERT  INTO ASISTNOW (ASIS_ID,ASIS_ING, ASIS_ZONA,ASIS_FECHA, ASIS_HORA,ASIS_TIPO,ASIS_RES,ASIS_F,ASIS_FN,ASIS_HN,ASIS_PRINT,ASIS_NOVEDAD,ASIS_MAIL,ASIS_MM) SELECT '$id',GETDATE(), '170.17.0.109', CONVERT(DATE,GETDATE()), left(convert(nvarchar(20),CONVERT(TIME,GETDATE())),8),'WEB/TTRAB', 'OK', 2, CONVERT(DATE,GETDATE()), GETDATE(), 2, NULL, 1, NULL;";
                $conn->query($sql);         
                //update        
                $update = "UPDATE ASISTNOW SET ASIS_NOVEDAD='IP-> $ip || EQUIPO-> $equipo'  WHERE ASIS_ID='$id' AND ASIS_ING=(select max(asis_ing) from asistnow where asis_id='$id');";
                $conn->query($update); 
            
            } catch (Exception $e) {
                print_r("ERROR");
                //echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            }
            
            try {
                 //consulta marcacion y muestra
                $sql = "SELECT ASIS_HORA FROM ASISTNOW WHERE ASIS_ID='$id' AND ASIS_ING=(select max(asis_ing) from asistnow where asis_id='$id');";
                $res=$conn->query($sql);
                foreach ($conn->query($sql) as $row){ 
                    echo "\n";           
                    print($row['ASIS_HORA']."<br>");
                 
                 }   
            } catch (Exception $e) {
                print_r("NO SE PUDO CONSULTAR.");
                //echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            }

            try {
                //consulta las maraciones del dia de hoy
               $sql = "SELECT ASIS_HORA, ASIS_FECHA, CAST( GETDATE() AS Date )  FROM ASISTNOW WHERE ASIS_ID=$id AND CAST( GETDATE() AS Date )=ASIS_FECHA ORDER BY 1 ASC;";
               $res=$conn->query($sql);
               print("Sus marcaciones del dia de hoy fueron:"."<br>");
               foreach ($conn->query($sql) as $row){
                     //echo nl2br($row['ASIS_HORA']."\n",false);   
                   print($row['ASIS_HORA']."<br>");                                                  
                }   
                } catch (Exception $e) {
               print_r("NO SE PUDO CONSULTAR.");
               //echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            }
        
        }
                         

    }     
   
?>

