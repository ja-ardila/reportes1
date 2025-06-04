<?php
require('fpdf/fpdf.php');

$fecha = $_GET['fecha'];
$empresa = $_GET['empresa'];
$nit = $_GET['nit'];
$direccion = ($_GET['direccion']);
$telefono = $_GET['telefono'];
$contacto = $_GET['contacto'];
$email = $_GET['email'];
$ciudad = $_GET['ciudad'];
$fechai = $_GET['fechai'];
$fechac = $_GET['fechac'];
$horai = $_GET['horai'];
$horac = $_GET['horac'];
$servicior = $_GET['servicior'];
$tiposervicio = $_GET['tiposervicio'];
$informe = $_GET['informe'];
$informe = str_replace("·$", "\n", $informe);
$observaciones = $_GET['observaciones'];
$observaciones = str_replace("·$", "\n", $observaciones);
$cedulat = $_GET['cedulat'];
$nombret = $_GET['nombret'];
$firma = $_GET['firma'];
$cedulae = $_GET['cedulae'];
$nombree = $_GET['nombree'];
$firmae = $_GET['firmae'];
$nreporte = $_GET['nreporte'];
$imagenesrecibir = stripslashes($_GET["imagenes"]);
$imagenesrecibir = urldecode($imagenesrecibir);
$imagenes = unserialize($imagenesrecibir);

class PDF extends FPDF {

    function CellImageCentered($file, $cellWidth, $cellHeight){
        //Obtener dimensión imagen
        list($imageWidth, $imageHeight) = getimagesize($file);

        //Calcular escala para ajustar la imagen dentro de la celda
        $scale = min($cellWidth / $imageWidth, $cellHeight / $imageHeight);
        $imageWidth *= $scale;
        $imageHeight *= $scale;

        //Calcular la posición X e Y para centrar la imagen dentro de la celda
        $x = $this->GetX() + ($cellWidth - $imageWidth) / 2;
        $y = $this->GetY() + ($cellHeight - $imageHeight) / 2;

        //Agregar la imagen a la posición calculada
        $this->Image($file, $x, $y, $imageWidth, $imageHeight);
    }
    function CellImageCenteredLogo($file, $cellWidth, $cellHeight){
        //Obtener dimensión imagen
        list($imageWidth, $imageHeight) = getimagesize($file);

        //Calcular escala para ajustar la imagen dentro de la celda
        $scale = min($cellWidth / $imageWidth, $cellHeight / $imageHeight);
        $imageWidth *= $scale*0.65;
        $imageHeight *= $scale*0.65;

        //Calcular la posición X e Y para centrar la imagen dentro de la celda
        $x = $this->GetX() + ($cellWidth - $imageWidth) / 2;
        $y = $this->GetY() + ($cellHeight - $imageHeight) / 2;

        //Agregar la imagen a la posición calculada
        $this->Image($file, $x, $y, $imageWidth, $imageHeight);
    }
    function CellImageCenteredFirma($file, $cellWidth, $cellHeight){
        //Obtener dimensión imagen
        list($imageWidth, $imageHeight) = getimagesize($file);

        //Calcular escala para ajustar la imagen dentro de la celda
        $scale = min($cellWidth / $imageWidth, $cellHeight / $imageHeight);
        $imageWidth *= $scale*0.95;
        $imageHeight *= $scale*0.95;

        //Calcular la posición X e Y para centrar la imagen dentro de la celda
        $x = $this->GetX() + ($cellWidth - $imageWidth) / 2;
        $y = $this->GetY() + ($cellHeight - $imageHeight) / 2;

        //Agregar la imagen a la posición calculada
        $this->Image($file, $x, $y, $imageWidth, $imageHeight);
    }
}

$pdf = new PDF();

$pdf->SetTitle("Reporte No. $nreporte", false);

// Añadir una página
$pdf->AddPage();


// Imprimir la información utilizando las variables
$pdf->Cell(95, 40, $pdf->CellImageCenteredLogo("logo.png", 95, 40), 1, 0, "C");
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(47.5, 40, iconv('UTF-8', 'windows-1252', "REPORTE GENERAL"), 1, 0, "C");
$x1 = $pdf->GetX();
$pdf->SetX($x1);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(47.5, 12, iconv('UTF-8', 'windows-1252', "ORDEN DE SERVICIO"), 1, 1, "C");
$pdf->SetX($x1);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(47.5, 8, iconv('UTF-8', 'windows-1252', $nreporte), 1, 1, "C");
$pdf->SetX($x1);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(47.5, 12, iconv('UTF-8', 'windows-1252', "FECHA"), 1, 1, "C");
$pdf->SetX($x1);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(47.5, 8, iconv('UTF-8', 'windows-1252', $fecha), 1, 1, "C");

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(190, 10, iconv('UTF-8', 'windows-1252', "DATOS DEL CLIENTE"), 1, 1, "C");

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(95, 8, iconv('UTF-8', 'windows-1252', "Empresa: ".$empresa), 1, 0, "L");
$pdf->Cell(47.5, 8, iconv('UTF-8', 'windows-1252', "Nit: ".$nit), 1, 0, "L");
$pdf->Cell(47.5, 8, iconv('UTF-8', 'windows-1252', "Teléfono: ".$telefono), 1, 1, "L");
$pdf->Cell(95, 8, iconv('UTF-8', 'windows-1252', "Dirección: ".$direccion), 1, 0, "L");
$pdf->Cell(95, 8, iconv('UTF-8', 'windows-1252', "E-mail: ".$email), 1, 1, "L");
$pdf->Cell(95, 8, iconv('UTF-8', 'windows-1252', "Contacto: ".$contacto), 1, 0, "L");
$pdf->Cell(95, 8, iconv('UTF-8', 'windows-1252', "Ciudad: ".$ciudad), 1, 1, "L");

$pdf->SetY($pdf->GetY()+8);
$pdf->Cell(47.5, 8, iconv('UTF-8', 'windows-1252', "Fecha inicio:"), 1, 0, "C");
$pdf->Cell(47.5, 8, $fechai, 1, 0, "C");
$pdf->Cell(47.5, 8, iconv('UTF-8', 'windows-1252', "Fecha cierre:"), 1, 0, "C");
$pdf->Cell(47.5, 8, $fechac, 1, 1, "C");
$pdf->Cell(47.5, 8, iconv('UTF-8', 'windows-1252', "Hora inicio:"), 1, 0, "C");
$pdf->Cell(47.5, 8, $horai, 1, 0, "C");
$pdf->Cell(47.5, 8, iconv('UTF-8', 'windows-1252', "Hora cierre:"), 1, 0, "C");
$pdf->Cell(47.5, 8, $horac, 1, 1, "C");

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(190, 8, iconv('UTF-8', 'windows-1252', "SERVICIO REPORTADO:"), "LR", 1, "L");
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(190, 8, iconv('UTF-8', 'windows-1252', $servicior), "LRB", 1, "L");
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(190, 8, iconv('UTF-8', 'windows-1252', "TIPO DE SERVICIO:"), "LR", 1, "L");
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(190, 8, iconv('UTF-8', 'windows-1252', $tiposervicio), "LRB", 1, "L");

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(95, 8, iconv('UTF-8', 'windows-1252', "Nombre del técnico / ingeniero"), 1, 0, "C");
$pdf->Cell(95, 8, iconv('UTF-8', 'windows-1252', $nombret), 1, 1, "C");

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(190, 8, iconv('UTF-8', 'windows-1252', "INFORME TÉCNICO:"), "LR", 1, "L");
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(190, 3, iconv('UTF-8', 'windows-1252', $informe), "LRB", "L", 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(190, 8, iconv('UTF-8', 'windows-1252', "OBSERVACIONES:"), "LR", 1, "L");
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(190, 3, iconv('UTF-8', 'windows-1252', $observaciones), "LRB", "L", 0);


$y = $pdf->GetY();
$i = 0;
$j = 0;
$cellHeight = 44.3;
$cellWidth = 190/3;
foreach($imagenes as $imagen){
    $j = $j+1;
    if($pdf->GetY()>233){
        $pdf->AddPage();
    }
    if($j == 3){
        $i = 1;
        $j = 0;
    }else{
        $i = 0;
    }
    $pdf->Cell($cellWidth, $cellHeight, $pdf->CellImageCentered("imagenes/".$imagen, $cellWidth, $cellHeight), 1, $i, "R");
    
}
if ($j == 1){
    $pdf->Cell($cellWidth, $cellHeight, null, 1, 0, "R");
    $pdf->Cell($cellWidth, $cellHeight, null, 1, 1, "R");
}elseif($j == 2){
    $pdf->Cell($cellWidth, $cellHeight, null, 1, 1, "R");
}
$y = $pdf->GetY();
$pdf->SetY($y+4);
$pdf->SetFont('Arial', 'B', 8);
if($pdf->GetY()>233){
        $pdf->AddPage();
    }
$pdf->Cell(95, 8, iconv('UTF-8', 'windows-1252', "ENTREGA:"), 1, 0, "L");
$pdf->Cell(95, 8, iconv('UTF-8', 'windows-1252', "RECIBO A SATISFACCIÓN CLIENTE:"), 1, 1, "L");
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(95, 24, $pdf->CellImageCenteredFirma("firmas/$firma", 95, 24), "LRT", 0, "L");

if(empty($firmae)){
    $firmaePDF = null;
}else{
    $firmaePDF = $pdf->CellImageCenteredFirma("signatures/$firmae", 95, 24);
}

$pdf->Cell(95, 24, $firmaePDF, "LRT", 1, "L");
$pdf->Cell(95, 5, iconv('UTF-8', 'windows-1252', "CC: $cedulat"), "LR", 0, "C");
$pdf->Cell(95, 5, iconv('UTF-8', 'windows-1252', "Nombre encargado: $nombree"), "LR", 1, "C");
$pdf->Cell(95, 5, iconv('UTF-8', 'windows-1252', "Firma y cédula"), "LRB", 0, "C");
$pdf->Cell(95, 5, iconv('UTF-8', 'windows-1252', "CC: $cedulae"), "LRB", 1, "C");

$y = $pdf->GetY();
$pdf->SetY($y+4);
$pdf->Cell(190, 5, iconv('UTF-8', 'windows-1252', "Cra 7 # 180 - 30 Torre A Oficina 304 PBX 3004048"), 1, 1, "C");
$pdf->Cell(190, 5, iconv('UTF-8', 'windows-1252', "info@h323.com.co"), 1, 1, "C", 0, "mailto:info@h323.com.co");


// Generar el PDF
$pdf->Output();
?>