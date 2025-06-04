<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Retrieve the username from the session
$username = $_SESSION['username'];
$nombreusuario = $_SESSION['nombre'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reportes H323</title>
    <style>
        .container {
            overflow: auto;
            border-radius: 5px;
            width: 75%;
            margin-left: auto;
            margin-right: auto;
            background-color: #fff;
            margin-bottom: 10px;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
        }
        .sidebyside {
            padding: 1%;
            margin: 0;
            box-sizing: border-box; /* Incluir padding y border en el ancho total */
        }
        #nr {
            width: 4%; /* Ancho ajustado */
            text-align:center;
        }
        #fecha {
            width: 15%; /* Ancho ajustado */
            text-align:center;
        }
        #empresa {
            width: 48%; /* Ancho ajustado */
            text-align:center;
        }
        #editar, #imprimir {
            width: 15%; /* Ancho ajustado */
            text-align:center;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .report-link, a {
            display: inline-block;
            padding: 10px 40px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
        }
        .report-link:hover, a:hover {
            background-color: #0056b3;
        }
        p {
            margin: 0;
            padding: 10px 0;
        }
        ul {
            padding: 0;
            list-style: none;
        }
        /* Estilos para los botones flotantes */
        .float-button {
            position: fixed;
            width: 50px;
            height: 50px;
            bottom: 20px;
            right: 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 50%;
            text-align: center;
            line-height: 50px;
            font-size: 18px;
            cursor: pointer;
        }
        .float-button:hover {
            background-color: #0056b3;
        }
        .top-button {
            bottom: 80px; /* Espacio entre los botones */
        }
        /* Ajustes para pantallas pequeñas */
        @media screen and (max-width: 1090px) {
            body {
                font-size: 18px;
            }
            h1 {
                font-size: 24px;
            }
            a, .report-link {
                font-size: 18px;
                padding: 8px 16px;
            }
            .container {
                width: 90%;
                margin-bottom: 10px;
            }
            .sidebyside {
                width: 100%;
                display: block;
                text-align: left;
                font-size: 16px;
            }
        }
        @media screen and (max-width: 768px) {
            body {
                font-size: 16px;
            }
            h1 {
                font-size: 22px;
            }
            a, .report-link {
                font-size: 16px;
                padding: 6px 12px;
            }
            .container {
                width: 95%;
            }
            .sidebyside {
                font-size: 14px;
                padding: 5px;
            }
        }
    </style>
    <script>
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        function scrollToBottom() {
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        }
    </script>
</head>
<body>
    <h1>Bienvenido a reportes, 
    <?php
    echo $nombreusuario;
    ?>
    </h1>
    
    <!-- Botones flotantes -->
    <button class="float-button top-button" onclick="scrollToTop()">&#8679;</button>
    <button class="float-button" onclick="scrollToBottom()">&#8681;</button>

    <ul>
        <div class='container'>
            <div class='sidebyside' style='text-align:center; flex: 1 1 48%'><strong><a href="reportes.php">Crear reporte nuevo</a></strong></div>
            <div class='sidebyside' style='text-align:center; flex: 1 1 48%'><strong><a href="logout.php">Cerrar Sesión</a></strong></div>
        </div>
        <div class='container'>
            <div class='sidebyside' id='nr'><strong>&nbsp;&nbsp;&nbsp;#</strong></div>
            <div class='sidebyside' id='fecha'><strong>Fecha</strong></div>
            <div class='sidebyside' id='empresa'><strong>Empresa</strong></div>
            <div class='sidebyside' id='editar'><strong>Editar</strong></div>
            <div class='sidebyside' id='imprimir'><strong>Imprimir</strong></div>
        </div>

        <?php
        $archivo = "reportes.txt";
        $lineas = file($archivo); // Leer el archivo y almacenar cada línea en un array
        $id = "";
        $empresa2 = "";
        $imagenes = array();
        foreach ($lineas as $linea) {
            $linea = trim($linea);
            if ($linea != "") {
                if (strpos($linea, "- Fecha: ") === 0) {
                    $fecha = substr($linea, 2);
                    $fecha2 = substr($fecha, 7);
                    $fecha3 = substr($fecha, 7, -9);
                    echo "<div class='sidebyside' id='fecha'><p>$fecha3</p></div>"; // Imprimir la línea de información
                } elseif (strpos($linea, "- Empresa: ") === 0) {
                    $empresa = substr($linea, 2);
                    $empresa2 = substr($empresa, 9);
                    echo "<div class='sidebyside' id='empresa'><p>$empresa2</p></div>";
                } elseif (strpos($linea, "- Nit: ") === 0) {
                    $nit = substr($linea, 7);
                } elseif (strpos($linea, "- Dirección: ") === 0) {
                    $direccion = substr($linea, 14);
                } elseif (strpos($linea, "- Teléfono: ") === 0) {
                    $telefono = substr($linea, 13);
                } elseif (strpos($linea, "- Contacto: ") === 0) {
                    $contacto = substr($linea, 12);
                } elseif (strpos($linea, "- Email: ") === 0) {
                    $email = substr($linea, 9);
                } elseif (strpos($linea, "- Ciudad: ") === 0) {
                    $ciudad = substr($linea, 10);
                } elseif (strpos($linea, "- Fecha inicio: ") === 0) {
                    $fechai = substr($linea, 16);
                } elseif (strpos($linea, "- Fecha cierre: ") === 0) {
                    $fechac = substr($linea, 16);
                } elseif (strpos($linea, "- Hora inicio: ") === 0) {
                    $horai = substr($linea, 15);
                } elseif (strpos($linea, "- Hora cierre: ") === 0) {
                    $horac = substr($linea, 15);
                } elseif (strpos($linea, "- Servicio reportado: ") === 0) {
                    $servicior = substr($linea, 22);
                } elseif (strpos($linea, "- Tipo de servicio: ") === 0) {
                    $tiposervicio = substr($linea, 20);
                } elseif (strpos($linea, "- Informe: ") === 0) {
                    $informe = substr($linea, 11);
                } elseif (strpos($linea, "- Observaciones: ") === 0) {
                    $observaciones = substr($linea, 17);
                } elseif (strpos($linea, "- Cédula técnico: ") === 0) {
                    $cedulat = substr($linea, 20);
                } elseif (strpos($linea, "- Nombre técnico: ") === 0) {
                    $nombret = substr($linea, 19);
                } elseif (strpos($linea, "- Firma técnico: ") === 0) {
                    $firma = substr($linea, 18);
                } elseif (strpos($linea, "- Cédula encargado: ") === 0) {
                    $cedulae = substr($linea, 21);
                } elseif (strpos($linea, "- Nombre encargado: ") === 0) {
                    $nombree = substr($linea, 20);
                } elseif (strpos($linea, "- Firma encargado: ") === 0) {
                    $firmae = substr($linea, 19);
                } elseif (strpos($linea, "- Número de reporte: ") === 0) {
                    $nreporte = substr($linea, 22);
                    echo "<div class='container'><div class='sidebyside' id='nr'><p><strong>$nreporte</strong></p></div>"; // Imprimir el nuevo ítem en negrita
                } elseif (strpos($linea, "- Imagen: ") === 0) {
                    $imagenes[] = substr($linea, 10);
                } elseif (strpos($linea, "ID: ") === 0) {
                    $id = substr($linea, 4);
                }
            } else {
                $imagenesenvio = serialize($imagenes);
                $imagenesenvio = urlencode($imagenesenvio);
                echo "<div class='sidebyside' id='editar'><a href='editar.php?id=$id&fecha=$fecha2&empresa=$empresa2&firma=$firma&nit=$nit&direccion=".urlencode($direccion)."&telefono=$telefono&contacto=$contacto&email=$email&ciudad=$ciudad&fechai=$fechai&fechac=$fechac&horai=$horai&horac=$horac&servicior=$servicior&tiposervicio=$tiposervicio&informe=".urlencode($informe)."&observaciones=".urlencode($observaciones)."&cedulat=$cedulat&nombret=$nombret&cedulae=$cedulae&nombree=$nombree&firmae=$firmae&nreporte=$nreporte&imagenes=$imagenesenvio'>Editar</a></div>";
                echo "<div class='sidebyside' id='imprimir'><a href='pdf.php?fecha=$fecha2&empresa=$empresa2&firma=$firma&nit=$nit&direccion=".urlencode($direccion)."&telefono=$telefono&contacto=$contacto&email=$email&ciudad=$ciudad&fechai=$fechai&fechac=$fechac&horai=$horai&horac=$horac&servicior=$servicior&tiposervicio=$tiposervicio&informe=".urlencode($informe)."&observaciones=".urlencode($observaciones)."&cedulat=$cedulat&nombret=$nombret&cedulae=$cedulae&nombree=$nombree&firmae=$firmae&nreporte=$nreporte&imagenes=$imagenesenvio'>Imprimir</a></div>";
                $imagenes = array();
                echo "</div>";
            }
        }
        ?>
    </ul>
</body>
</html>
