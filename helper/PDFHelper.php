<?php
require ('fpdf184/fpdf.php');
class PDFHelper
{
    public function generarPDF($id, $datosCompletos)

    {
        $nombreImagen = $id.'-qr.png';
        $pdf = new FPDF();



        $pdf->AddPage();

        //Marca de agua
        $pdf->SetFont('Arial','B',50);
        $pdf->SetTextColor(255, 193, 105);
        $pdf->Text(50,110,'G A U C H O');
        $pdf->Text(50,130,'R O C K E T');
        $pdf->Text(52,150,'V I A J E S');
        $pdf->Text(30,170,'E S P A C I A L E S');

        // Logo
        $pdf->Image('public/images/Cohete.png',110,15, 10, 10);
        // Arial bold 15
        $pdf->SetFont('Arial','B',15);
        $pdf->SetTextColor(0,0,0);

        $pdf->Cell(80,20,'Pase de abordaje - Gaucho Rocket S. A',0,'C');
        //QR
        $pdf->Image($nombreImagen,150,20,50,50);

        $pdf->SetFont('Arial','',12);
        $pdf->Cell(80,20,'Nombre y apellido del pasajero: '.$datosCompletos['nombre'] .'  '.$datosCompletos['apellido'] ,0,'C');
        $pdf->Cell(80,20,'Dni: '. $datosCompletos['dni'],0,'C');
        $pdf->Cell(80,20,'Salida desde: ' . $datosCompletos['origen'],0,'C');
        $pdf->Cell(80,20,'Llegada a: ' . $datosCompletos['destino'],0,'C');
        $pdf->Cell(80,20,'Tipo de viaje: '. $datosCompletos['tipo_viaje'],0,'C');
        $pdf->Cell(80,20,'Cabina: '. $datosCompletos['cabina'],0,'C');
        $pdf->Cell(80,20,'Servicio: ' . $datosCompletos['servicio'],0,'C');
        $pdf->Cell(80,20,'Fecha y hora de partida: '.$datosCompletos['fecha_partida'],0,'C');
        $pdf->Cell(80,20,'Total abonado: $' . $datosCompletos['total'],0,'C');
        $pdf->Output('D', 'Pase-de-abordaje.pdf');
        $pdf->Output('F', 'Pase-de-abordaje.pdf');
    }

}