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
    <link href='https://use.fontawesome.com/releases/v5.7.2/css/all.css' rel='stylesheet'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif
        }    

        .logo {
           width: 100%;
    	   text-align: center;
        }
        body {
            min-height: 100vh;
            background: linear-gradient(to bottom, #E6DDDB, #004683)
        }

        .wrapper {
            max-width: 450px;
            margin: 50px auto;
            padding: 20px 30px;
            min-height: 300px;
            background-color: #ffffff27;
            border-top: 1px solid #ffffff6e;
            border-left: 1px solid #ffffff6e;
            border-radius: 15px
        }
 
        .wrapper .h5 {
            color: #ddd
        }

        .wrapper .form-group {
            border-bottom: 1px solid #ccc;
            margin-bottom: 1.5rem
        }

        .wrapper .form-group:hover {
            border-bottom: 1px solid #eee
        }

        .wrapper .form-group .icon {
            color: #e8e8e8
        }

        .wrapper .form-group .form-control {
            background: inherit;
            border: none;
            border-radius: 0px;
            box-shadow: none;
            color: #e9e9e9
        }

        .wrapper .form-group input::placeholder {
            color: #ccc
        }

        .wrapper .form-group input:focus::placeholder {
            opacity: 0
        }

        .wrapper .form-group .fa-phone {
            transform: rotate(90deg)
        }

        .wrapper .option {
            color: #ccc;
            display: block;
            position: relative;
            padding-left: 25px;
            margin-bottom: 12px;
            cursor: pointer;
            user-select: none
        }

        .wrapper .option:hover {
            color: #eee
        }

        .wrapper .option input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0
        }

        .wrapper .checkmark {
            position: absolute;
            top: 3px;
            left: 0;
            height: 18px;
            width: 18px;
            background-color: inherit;
            border: 2px solid #ccc;
            border-radius: 2px
        }

        .wrapper .option input:checked~.checkmark {
            transition: 300ms ease-in-out all
        }

        .wrapper .checkmark:after {
            content: "\2713";
            position: absolute;
            display: none;
            font-weight: 600;
            color: #FFF;
            font-size: 0.9rem
        }

        .wrapper .option input:checked~.checkmark:after {
            display: block
        }

        .wrapper .option .checkmark:after {
            left: 2px;
            top: -4px;
            width: 5px;
            height: 10px
        }

        .wrapper .btn.btn-primary {
            position: relative;
            color: #eee;
            padding: 0.3rem 1rem;
            border-radius: 20px;
            border: 1px solid #ddd;
            background-color: inherit;
            box-shadow: none;
            overflow: hidden
        }

        .wrapper .btn.btn-primary:hover {
            background-color: #b4b4b433;
            color: #fff
        }

        .wrapper .terms {
            color: #bbb;
            font-size: 0.85rem;
            text-align: center
        }

        .wrapper .terms a {
            text-decoration: none;
            color: #eee
        }

        .wrapper .terms a:hover {
            color: #fff
        }

        .wrapper .connect {
            position: relative
        }

        .wrapper .connect::after {
            content: "RECUERDE";
            font-weight: bold;
            position: absolute;
            top: -12px;
            width:80px;
            left: 39%;
            text-align: center;
            color: #eee;
            z-index: 100;
            background-color: rgba(255, 255, 255, 0.315);
            background-color: #1f5588
        }
        @media(max-width: 460px) {
            .wrapper {
                margin: 15px;
                padding: 20px
            }

            .wrapper .connect::after {
                left: 38%
            }
        }

        @media(max-width: 345px) {
            .wrapper .connect::after {
                left: 32%
            }
        }
        #errmsg
        {
        color: white;
        }
        .btn-primary, .btn-primary:hover, .btn-primary:active, .btn-primary:visited {
            background-color: #023e8a!important;
            font-weight : bold ;
        }
    </style>
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
                    <span id="errmsg"></span> 
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
