<?php
require ('fpdf184/fpdf.php');
class PDFHelper
{
    public function generarPDF($id, $datosCompletos)

    {
        $nombreImagen = $id.'-qr.png';
        $pdf = new FPDF();



        $pdf->AddPage();

        //HEADER
        // Logo
        $pdf->Image('public/images/Cohete.png',80,8,33);
        // Arial bold 15
        $pdf->SetFont('Arial','B',15);

        // Título
        $pdf->Ln(10);
        $pdf->Cell(80,20,'Pase de abordaje',0,'C');
        // Salto de línea
        $pdf->Ln(20);
        $pdf->Image($nombreImagen,80,50,33);
        $pdf->Ln(10);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(80,20,'Nombre:',0,'C');
        $pdf->Ln(0.1);
        $pdf->Cell(80,20,$datosCompletos['nombre'],0,'C');
        $pdf->Ln(1);
        $pdf->Cell(80,20,'Apellido:',0,'C');
        $pdf->Ln(1);
        $pdf->Cell(80,20,$datosCompletos['apellido'],0,'C');
        $pdf->Ln(1);
        $pdf->Cell(80,20,'Dni:',0,'C');
        $pdf->Ln(1);
        $pdf->Cell(80,20,$datosCompletos['dni'],0,'C');
        $pdf->Ln(1);
        $pdf->Cell(80,20,'Origen:',0,'C');
        $pdf->Ln(1);
        $pdf->Cell(80,20,$datosCompletos['origen'],0,'C');
        $pdf->Ln(1);
        $pdf->Cell(80,20,'Destino:',0,'C');
        $pdf->Ln(1);
        $pdf->Cell(80,20,$datosCompletos['destino'],0,'C');
        $pdf->Ln(1);
        $pdf->Cell(80,20,'Tipo de viaje:',0,'C');
        $pdf->Ln(1);
        $pdf->Cell(80,20, $datosCompletos['tipo_viaje'],0,'C');
        $pdf->Ln(1);
        $pdf->Cell(80,20,'Cabina:',0,'C');
        $pdf->Ln(1);
        $pdf->Cell(80,20,  $datosCompletos['cabina'],0,'C');
        $pdf->Ln(1);
        $pdf->Cell(80,20,'Servicio:',0,'C');
        $pdf->Ln(1);
        $pdf->Cell(80,20,  $datosCompletos['servicio'],0,'C');

        $pdf->Ln(1);
        $pdf->Cell(80,20,'Fecha y hora de partida:',0,'C');
        $pdf->Ln(1);
        $pdf->Cell(80,20,  $datosCompletos['fecha_partida'],0,'C');
        $pdf->Ln(1);
        $pdf->Cell(80,20,'Total abonado:',0,'C');
        $pdf->Ln(1);
        $pdf->Cell(80,20, $datosCompletos['total'],0,'C');




        $pdf->Output('D', 'Pase-de-abordaje.pdf');
    }

}