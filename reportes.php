<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
} else {
    $user = $_SESSION['username'];
}

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $empresa = $_POST['empresa'];
    $nit = $_POST['nit'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $contacto = $_POST['contacto'];
    $email = $_POST['email'];
    $ciudad = $_POST['ciudad'];
    $fechai = $_POST['fechai'];
    $fechac = $_POST['fechac'];
    $horai = $_POST['horai'];
    $horac = $_POST['horac'];
    $servicior = $_POST['servicior'];
    $tiposervicio = $_POST['tiposervicio'];
    $informe = $_POST['informe'];
    $informe = preg_replace("/[\r\n]+/", "·$"."·$", $informe);
    $observaciones = $_POST['observaciones'];
    $observaciones = preg_replace("/[\r\n]+/", "·$"."·$", $observaciones);
    $cedulat = $_POST['cedulat'];
    $nombret = $_POST['nombret'];
    $firma = $_POST['firma'];
    $cedulae = $_POST['cedulae'];
    $nombree = $_POST['nombree'];
    $firmae = $_POST['signature'];

    // Ruta de almacenamiento de las imágenes
    $rutaImagenes = 'imagenes/';

    // Crear un array para almacenar los nombres de las imágenes
    $nombresImagenes = [];

    if (isset($_FILES['imagenes'])) {
        // Recorrer todas las imágenes cargadas
        foreach ($_FILES['imagenes']['tmp_name'] as $index => $tmpName) {
            // Verificar si se recibió correctamente el archivo temporal de la imagen
            if (is_uploaded_file($tmpName)) {
                // Obtener el nombre original de la imagen
                $nombreImagen = $_FILES['imagenes']['name'][$index];

                // Crear un nombre único para la imagen
                $nombreUnico = uniqid() . '_' . $nombreImagen;

                // Ruta y nombre de archivo para guardar en formato JPEG
                $rutaDestino = $rutaImagenes . basename($nombreUnico, '.' . pathinfo($nombreUnico, PATHINFO_EXTENSION)) . '.jpeg';

                // Convertir la imagen a formato JPEG
                $imagen = imagecreatefromstring(file_get_contents($tmpName));
                imagejpeg($imagen, $rutaDestino, 100);
                imagedestroy($imagen);

                // Agregar el nombre de la imagen convertida al array
                $nombresImagenes[] = basename($rutaDestino);
            } else {
                // Error al cargar el archivo temporal de la imagen
                echo "Error al cargar la imagen número " . ($index + 1) . "<br>";
            }
        }
    }

    // Guardar la firma como imagen en el servidor, si se proporcionó
    $file = '';
    if (!empty($firmae)) {
        $firmae = str_replace('data:image/png;base64,', '', $firmae);
        $firmae = str_replace(' ', '+', $firmae);
        $data = base64_decode($firmae);
        $file = uniqid() . '.png';
        file_put_contents('signatures/'.$file, $data);
    } else {
        $file = 0;
    }

    // Crear el reporte en el archivo de texto
    $archivoReportes = 'reportes.txt';
    $id = uniqid();
    date_default_timezone_set('America/Bogota');
    $fecha = date("d/m/Y H:i:s");
    $lineas = file($archivoReportes);
    $nreporte = intval($lineas[0]);
    $nuevoReporte = "ID: $id\n- Número de reporte: $nreporte\n- Usuario: $user\n- Fecha: $fecha\n- Empresa: $empresa\n- Nit: $nit\n- Dirección: $direccion\n- Teléfono: $telefono\n- Contacto: $contacto\n- Email: $email\n- Ciudad: $ciudad\n- Fecha inicio: $fechai\n- Fecha cierre: $fechac\n- Hora inicio: $horai\n- Hora cierre: $horac\n- Servicio reportado: $servicior\n- Tipo de servicio: $tiposervicio\n- Informe: $informe\n- Observaciones: $observaciones\n- Cédula técnico: $cedulat\n- Nombre técnico: $nombret\n- Firma técnico: $firma\n- Cédula encargado: $cedulae\n- Nombre encargado: $nombree\n- Firma encargado: $file\n";

    // Agregar los nombres de las imágenes al reporte
    foreach ($nombresImagenes as $nombreImagen) {
        $nuevoReporte .= "- Imagen: $nombreImagen\n";
    }

    $nuevoReporte .= "\n";
    file_put_contents($archivoReportes, $nuevoReporte, FILE_APPEND);

    // Sumar 1 al número de reportes
    $cont = file_get_contents($archivoReportes);
    $lineas = explode("\n", $cont);
    $lineas[0] = intval($lineas[0]) + 1;
    $contenidoModificado = implode("\n", $lineas);
    file_put_contents($archivoReportes, $contenidoModificado);

    // Enviar el correo electrónico
    $to = "lpoveda@h323.com.co, jardila@h323.com.co";
    $subject = "Nuevo Reporte Creado: Reporte No $nreporte - $empresa";
    $message = "
    <html>
    <head>
        <title>Nuevo Reporte Creado</title>
        <style>
            body {
                font-family: Arial, sans-serif;
            }
            .container {
                padding: 20px;
                background-color: #f4f4f4;
                border: 1px solid #ddd;
                border-radius: 5px;
                margin: 20px auto;
                max-width: 600px;
            }
            .header {
                font-size: 20px;
                font-weight: bold;
                margin-bottom: 20px;
            }
            .content {
                font-size: 16px;
            }
            .footer {
                margin-top: 20px;
                font-size: 14px;
                color: #666;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>Nuevo Reporte Creado</div>
            <div class='content'>
                Reporte No $nreporte creado para la empresa $empresa.
                <br />
                Para descargarlo por favor dirigirse a <a href='http://h323.com.co/reportes'>h323.com.co/reportes</a>.
            </div>
            <div class='footer'>
                Este es un correo generado automáticamente, por favor no responder.
            </div>
        </div>
    </body>
    </html>
    ";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: noreply@h323.com.co' . "\r\n";

    mail($to, $subject, $message, $headers);

    // Redireccionar a una página de éxito o mostrar un mensaje
    echo '<script>
        alert("Reporte creado exitosamente!");
        window.location.href = "listado.php";
    </script>';

    // Limpiar los datos del formulario
    $empresa = $nit = $direccion = $telefono = $contacto = $email = $ciudad = $fechac = $fechai = $horai = $horac = $informe = $observaciones = $cedulat = $nombret = $cedulae = $nombree = $servicior = $tiposervicio = $firmae = '';
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Página de Reportes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 20px;
        }
        
        form {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        label {
            display: block;
            margin-bottom: 10px;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="number"],
        input[type="file"],
        input[type="date"],
        input[type="time"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }
        
        textarea {
            max-width:100%;
            min-width:100%;
            min-height:100px;
        }
        
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
            font-size: 14px;
            color: #555;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("arrow.png");
            background-repeat: no-repeat;
            /*background-position: right center;*/
            background-position: 98% center;
            cursor: pointer;
        }
        input[type="file"] {
            cursor: pointer;
        }
        input[type="date"]::-webkit-inner-spin-button,
        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-inner-spin-button,
        input[type="time"]::-webkit-calendar-picker-indicator {
            display: none;
        }
        
        input[type="date"]::-webkit-input-placeholder,
        input[type="time"]::-webkit-input-placeholder {
            color: #999;
        }
        .botonregreso {
            border-radius: 5px;
            width:100%;
            max-width:440px;
            margin: 0 auto;
            background-color: #fff;
            margin-bottom: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .boton {
            width:15%; 
            height:auto;
            padding:2%;
            padding-left:20px;
            padding-right:auto;
            text-align:center;
        }
        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            font-size: 12px;
            text-decoration: none;
            border-radius: 4px;
        }
        a:hover {
            background-color: #0056b3;
        }
        @media screen and (max-width: 1090px) {
            form {
                max-width: 82%;
                margin: 0 auto;
                background-color: #fff;
                padding: 4%;
                border-radius: 4px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }
            .botonregreso {
                border-radius: 5px;
                width:100%;
                max-width:90%;
                margin: 0 auto;
                background-color: #fff;
                margin-bottom: 10px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }
            .boton {
                width:15%; 
                height:auto;
                padding:2%;
                padding-left:20px;
                padding-right:auto;
                text-align:center;
            }
            body {
                font-size: 40px;
            }
            a {
                font-size: 40px;
            }
            input[type="text"],
            input[type="password"],
            input[type="email"],
            input[type="number"],
            input[type="file"],
            input[type="date"],
            input[type="time"],
            textarea,
            select {
                width: 100%;
                padding: 10px;
                font-size: 40px;
                box-sizing: border-box;
            }
            input[type="submit"] {
                font-size: 40px;
            }
            textarea {
                min-height:500px;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var canvas = document.getElementById('signature-pad');
            var signaturePad = new SignaturePad(canvas);

            function resizeCanvas() {
                var ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
                signaturePad.clear();
            }

            window.addEventListener('resize', resizeCanvas);
            resizeCanvas();

            document.getElementById('clear').addEventListener('click', function () {
                signaturePad.clear();
            });

            document.querySelector('form').addEventListener('submit', function (e) {
                if (signaturePad.isEmpty()) {
                    var confirmWithoutSignature = confirm('No ha firmado el documento. ¿Desea continuar sin firma?');
                    if (!confirmWithoutSignature) {
                        e.preventDefault();
                        return;
                    }
                } else {
                    var dataUrl = signaturePad.toDataURL();
                    document.getElementById('signature').value = dataUrl;
                }
            });
        });
    </script>
</head>
<body>
    <h1>Generar Reporte</h1>
    <div class="botonregreso">
        <div class="boton">
            <a href="listado.php">Atrás</a>
        </div>
    </div>
    <div class="container">
        <form method="POST" action="" enctype="multipart/form-data">
            <label for="empresa">Empresa:</label>
            <input type="text" name="empresa" id="empresa" required><br><br>
    
            <label for="nit">Nit:</label>
            <input type="text" name="nit" id="nit" required><br><br>
    
            <label for="direccion">Dirección:</label>
            <input type="text" name="direccion" id="direccion" required><br><br>
    
            <label for="telefono">Teléfono:</label>
            <input type="number" name="telefono" id="telefono" required><br><br>
    
            <label for="contacto">Persona de contacto:</label>
            <input type="text" name="contacto" id="contacto" required><br><br>
    
            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" required><br><br>
    
            <label for="ciudad">Ciudad:</label>
            <input type="text" name="ciudad" id="ciudad" required><br><br>
    
            <label for="fechai">Fecha inicio:</label>
            <input type="date" name="fechai" id="fechai" required><br><br>
            <label for="fechac">Fecha Cierre:</label>
            <input type="date" name="fechac" id="fechac" required><br><br>
            <label for="horai">Hora Inicio:</label>
            <input type="time" name="horai" id="horai" required><br><br>
            <label for="horac">Hora Cierre:</label>
            <input type="time" name="horac" id="horac" required><br><br>
    
            <label for="servicior">Servicio reportado:</label>
            <input type="text" name="servicior" id="servicior" required><br><br>
    
            <label for="tiposervicio">Tipo de servicio:</label>
            <input type="text" name="tiposervicio" id="tiposervicio" required><br><br>
    
            <label for="informe">Informe:</label>
            <textarea name="informe" id="informe" required style="height:200px"></textarea><br><br>
    
            <label for="observaciones">Observaciones:</label>
            <textarea name="observaciones" id="observaciones" required></textarea><br><br>
    
            <label for="cedulat">Cédula técnico:</label>
            <input type="number" name="cedulat" id="cedulat" required><br><br>
    
            <label for="firma">Firma técnico:</label>
            <select id="firma" name="firma" require>
                <option value=""></option>
                <option value="firmaJA.png">Juan Andrés Ardila</option>
                <option value="firmaDB.png">Diego Bermúdez</option>
                <option value="firmaDG.png">Diego González</option>
            </select><br><br>
    
            <label for="nombret">Nombre técnico:</label>
            <input type="text" name="nombret" id="nombret" required><br><br>
    
            <label for="cedulae">Cédula encargado:</label>
            <input type="number" name="cedulae" id="cedulae" required><br><br>
    
            <label for="nombree">Nombre encargado:</label>
            <input type="text" name="nombree" id="nombree" required><br><br>
            
            <label for="firmae">Firma encargado:</label>
            <canvas id="signature-pad" style="border:1px solid #000; width: 100%; height: 200px;"></canvas>
            <button type="button" id="clear">Borrar Firma</button>
            <input type="hidden" name="signature" id="signature"><br><br>
    
            <label for="imagenes">Imágenes:</label>
            <input type="file" name="imagenes[]" id="imagenes" multiple><br><br>
    
            <input type="submit" value="Generar Reporte">
        </form>
    </div>
</body>
</html>