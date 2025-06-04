<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}else{
    $user = $_SESSION['username'];
}

$idg = $_GET['id'];
$fechag = $_GET['fecha'];
$empresag = $_GET['empresa'];
$nitg = $_GET['nit'];
$direcciong = ($_GET['direccion']);
$telefonog = $_GET['telefono'];
$contactog = $_GET['contacto'];
$emailg = $_GET['email'];
$ciudadg = $_GET['ciudad'];
$fechaig = $_GET['fechai'];
$fechacg = $_GET['fechac'];
$horaig = $_GET['horai'];
$horacg = $_GET['horac'];
$serviciorg = $_GET['servicior'];
$tiposerviciog = $_GET['tiposervicio'];
$informeg = $_GET['informe'];
$informeg = str_replace("·$", "\n", $informeg);
$observacionesg = $_GET['observaciones'];
$observacionesg = str_replace("·$", "\n", $observacionesg);
$cedulatg = $_GET['cedulat'];
$nombretg = $_GET['nombret'];
$firmag = $_GET['firma'];
$cedulaeg = $_GET['cedulae'];
$nombreeg = $_GET['nombree'];
$nreporteg = $_GET['nreporte'];
$firmaeg = $_GET['firmae'];
$imagenesrecibir = stripslashes($_GET["imagenes"]);
$imagenesrecibir = urldecode($imagenesrecibir);
$imagenesg = unserialize($imagenesrecibir);

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

    // Ruta de almacenamiento de las imágenes
    //$rutaImagenes = 'imagenes/';

    // Crear un array para almacenar los nombres de las imágenes
    //$nombresImagenes = [];    
    /*
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
    }*/

    // Crear el reporte en el archivo de texto
    $archivoReportes = 'reportes.txt';

    // Leer el contenido actual del archivo
    $contenido = file_get_contents($archivoReportes);

    $id = $idg;

    // Buscar el ítem principal por su ID
    $patron = "/ID: $id(.*?)(?=ID:|\z)/s";
    $matches = array();

    if (preg_match($patron, $contenido, $matches)) {
        // Reemplazar los campos correspondientes del ítem principal
        $reemplazo = "ID: $id\n";
        $reemplazo .= "- Número de reporte: $nreporteg\n";
        $reemplazo .= "- Usuario: $user\n";
        $reemplazo .= "- Fecha: $fechag\n";
        $reemplazo .= "- Empresa: $empresa\n";
        $reemplazo .= "- Nit: $nit\n";
        $reemplazo .= "- Dirección: $direccion\n";
        $reemplazo .= "- Teléfono: $telefono\n";
        $reemplazo .= "- Contacto: $contacto\n";
        $reemplazo .= "- Email: $email\n";
        $reemplazo .= "- Ciudad: $ciudad\n";
        $reemplazo .= "- Fecha inicio: $fechai\n";
        $reemplazo .= "- Fecha cierre: $fechac\n";
        $reemplazo .= "- Hora inicio: $horai\n";
        $reemplazo .= "- Hora cierre: $horac\n";
        $reemplazo .= "- Servicio reportado: $servicior\n";
        $reemplazo .= "- Tipo de servicio: $tiposervicio\n";
        $reemplazo .= "- Informe: $informe\n";
        $reemplazo .= "- Observaciones: $observaciones\n";
        $reemplazo .= "- Cédula técnico: $cedulat\n";
        $reemplazo .= "- Nombre técnico: $nombret\n";
        $reemplazo .= "- Firma técnico: $firma\n";
        $reemplazo .= "- Cédula encargado: $cedulae\n";
        $reemplazo .= "- Nombre encargado: $nombree\n";
        $reemplazo .= "- Firma encargado: $firmaeg\n";
        foreach($imagenesg as $imagen){
            $reemplazo .= "- Imagen: $imagen\n";
        }
        $reemplazo .= "\n";
        // ... Agrega los demás campos del formulario según sea necesario

        // Actualizar el contenido del archivo con el reemplazo
        $contenido = str_replace($matches[0], $reemplazo, $contenido);

        // Guardar los cambios en el archivo
        file_put_contents($archivoReportes, $contenido);

        // Redireccionar a una página de éxito o mostrar un mensaje de éxito
        echo '<script>
        alert("Reporte editado exitosamente!");
        window.location.href = "listado.php";
        </script>';
        
    } else {
        // El ítem principal no fue encontrado
        echo "No se encontró el ítem con ID: $id.";
    }

    // Limpiar los datos del formulario
    $empresa = $nit = $direccion = 
    $telefono = $contacto = $email = 
    $ciudad = $fechac = $fechai = 
    $horai = $horac = $informe = 
    $observaciones = $cedulat = $nombret = $cedulae = 
    $nombree = $servicior = $tiposervicio = '';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar reporte No. <?php echo $nreporteg; ?></title>
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
</head>
<body>
    <h1>Editar Reporte No. <?php echo $nreporteg; ?></h1>
    <div class="botonregreso">
        <div class="boton">
            <a href="listado.php">Atrás</a>
        </div>
    </div>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="empresa">Empresa:</label>
        <input type="text" name="empresa" id="empresa" value="<?php echo $empresag?>" required><br><br>

        <label for="nit">Nit:</label>
        <input type="text" name="nit" id="nit" value="<?php echo $nitg?>" required><br><br>

        <label for="direccion">Dirección:</label>
        <input type="text" name="direccion" id="direccion" value="<?php echo $direcciong?>" required><br><br>

        <label for="telefono">Teléfono:</label>
        <input type="number" name="telefono" id="telefono" value="<?php echo $telefonog?>" required><br><br>

        <label for="contacto">Persona de contacto:</label>
        <input type="text" name="contacto" id="contacto" value="<?php echo $contactog?>" required><br><br>

        <label for="email">E-mail:</label>
        <input type="email" name="email" id="email" value="<?php echo $emailg?>" required><br><br>

        <label for="ciudad">Ciudad:</label>
        <input type="text" name="ciudad" id="ciudad" value="<?php echo $ciudadg?>" required><br><br>

        <label for="fechai">Fecha inicio:</label>
        <input type="date" name="fechai" id="fechai" value="<?php echo $fechaig?>" required><br><br>
        <label for="fechac">Fecha Cierre:</label>
        <input type="date" name="fechac" id="fechac" value="<?php echo $fechacg?>" required><br><br>
        <label for="horai">Hora Inicio:</label>
        <input type="time" name="horai" id="horai" value="<?php echo $horaig?>" required><br><br>
        <label for="horac">Hora Cierre:</label>
        <input type="time" name="horac" id="horac" value="<?php echo $horacg?>" required><br><br>

        <label for="servicior">Servicio reportado:</label>
        <input type="text" name="servicior" id="servicior" value="<?php echo $serviciorg?>" required><br><br>

        <label for="tiposervicio">Tipo de servicio:</label>
        <input type="text" name="tiposervicio" id="tiposervicio" value="<?php echo $tiposerviciog?>" required><br><br>

        <label for="informe">Informe:</label>
        <textarea name="informe" id="informe" required style="height:200px"><?php echo $informeg?></textarea><br><br>

        <label for="observaciones">Observaciones:</label>
        <textarea name="observaciones" id="observaciones" required><?php echo $observacionesg?></textarea><br><br>

        <label for="cedulat">Cédula técnico:</label>
        <input type="number" name="cedulat" id="cedulat" value="<?php echo $cedulatg?>" required><br><br>

        <label for="firma">Firma técnico:</label>
        <select id="firma" name="firma" require>
            <option value=""></option>
            <option value="firmaJA.png" <?php if($firmag == 'firmaJA.png'){echo("selected");}?>>Juan Andrés Ardila</option>
            <option value="firmaDB.png"<?php if($firmag == 'firmaDB.png'){echo("selected");}?>>Diego Bermúdez</option>
            <option value="firmaDG.png"<?php if($firmag == 'firmaDG.png'){echo("selected");}?>>Diego González</option>
        </select><br><br>

        <label for="nombret">Nombre técnico:</label>
        <input type="text" name="nombret" id="nombret" value="<?php echo $nombretg?>" required><br><br>

        <label for="cedulae">Cédula encargado:</label>
        <input type="number" name="cedulae" id="cedulae" value="<?php echo $cedulaeg?>" required><br><br>

        <label for="nombree">Nombre encargado:</label>
        <input type="text" name="nombree" id="nombree" value="<?php echo $nombreeg?>" required><br><br>
        <!--
        <label for="imagenes">Imágenes:</label>
        <input type="file" name="imagenes[]" id="imagenes" multiple required><br><br>
        -->
        <input type="submit" value="Editar Reporte">
    </form>
</body>
</html>