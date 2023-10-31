<?php
require_once('conf.php');
?>
<!doctype html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Marcación GPA</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link href='https://use.fontawesome.com/releases/v5.7.2/css/all.css' rel='stylesheet'>
</head>
<body>
    <div class="wrapper">
        <form action="index.php" method="post" id="marcacionform">
        <div class="container-fluid">
                <div>
                    <div>
                        <img src="img/logo.png" class="logo img-responsive center-block" />
                    </div>
                </div>
            </div>
            <div class="h5 font-weight-bold text-center mb-3">Marcación teletrabajo</div>
            <div class="form-group d-flex align-items-center">
                <div class="icon"><span class="far fa-id-card"></span></div> <input autocomplete="off" type="text"
                    class="form-control" placeholder="Cédula" name="cedula" id="cedula" maxlength="10" required >
                    <span id="errmsg"></span> <!--Campo de cédula-->
            </div>
            <div class="form-group d-flex align-items-center">
                <div class="icon"><span class="far fa-user"></span></div> <input autocomplete="off" type="text"
                    class="form-control" placeholder="" name="usuario" id="usuario"  readonly>                  
            </div>
            <div class="form-group d-flex align-items-center">
                <div class="icon"><span class="far fa-clock"></span></div> <input autocomplete="off" type="text"
                    class="form-control" placeholder="" name="hora" id="hora"  readonly>
                    <div class="digital-clock">00:00:00</div>
            </div>
                <button class="btn btn-primary" type="submit" id="submit" name ="enviar" >Realizar Marcación</button>
                <div class="connect border-bottom mt-4 mb-4"></div>         
            <div class="terms mb-2"> Realizar sus 4 marcaciones diarias: 8:AM, 4:30 AM y su media hora de almuerzo. <a href="https://www.azuay.gob.ec"> Volver a la página de la prefectura</a>. </div>    
            
        </form>
    </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<!--Inicio cambios-->
<script src="js/validation.js" type="text/javascript">
    //código movido a la carpeta js--> validation.js
</script><!--Fin cambios-->
</body>
</html>
