<?php
    require_once('conf.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Marcación GPA</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <form id="marcacionform">
        <input type="text" id="cedula" placeholder="Cédula" maxlength="10">
        <input type="text" id="usuario" placeholder="Nombre" readonly>
        <button id="submit" type="submit">Realizar Marcación</button>
    </form>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <script src="js/validation.js"></script>
</body>
</html>
