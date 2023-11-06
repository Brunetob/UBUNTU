<?php
    require_once('conf.php');// Incluye el archivo de configuración para la base de datos
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Marcación GPA</title>
    <link rel="stylesheet" href="css/styles.css"><!-- Enlace a la hoja de estilos -->
</head>
<body>
    <form id="marcacionform"> <!-- Formulario de marcación -->
        <input type="text" id="cedula" placeholder="Cédula" maxlength="10"> <!-- Campo para ingresar la cédula -->
        <input type="text" id="usuario" placeholder="Nombre" readonly> <!-- Campo para mostrar el nombre, readonly para que no sea editable -->
        <button id="submit" type="submit">Realizar Marcación</button><!-- Botón para realizar la marcación -->
    </form>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> <!-- Carga de jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script> <!-- Carga de SweetAlert2, librería para mostrar alertas amigables -->
    <script src="js/validation.js"></script> <!-- Carga de un archivo JavaScript para la validación -->
</body>
</html>
