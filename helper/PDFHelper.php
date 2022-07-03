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
    public function generarPDFParaTasaDeOcupacionPorViaje($tasaDeOcupacion){
        $pdf = new FPDF();
    
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',15);
        $pdf->Image('public/images/Cohete.png',80,8,33);
        $pdf->Ln(10);
        // Arial bold 15
 
        $pdf->Cell(80,20,'Tasa de Ocupacion Por Viaje',0,'C');
        $pdf->Ln(20);
        foreach ($tasaDeOcupacion as $tasa) {
            $pdf->Cell(80,20,$tasa['id_viaje'],0,'C');
            $pdf->Cell(80,20,$tasa['Asientos Ocupados'],0,'C');
            $pdf->Cell(80,20,$tasa['Asientos Totales'],0,'C');
            $pdf->Cell(80,20,$tasa['Tasa'],0,'C');
        }
       
        $pdf->Output('D', 'Tasa-De-Ocupacion-Por-Viaje.pdf');
    }



    
    public function generarPDFParaFacturacionMensual($facturacionMensual){
        $pdf = new FPDF();
    
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',15);
        $pdf->Image('public/images/Cohete.png',80,8,33);
        $pdf->Ln(10);
        // Arial bold 15
 
        $pdf->Cell(80,20,'Facturacion Mensual',0,'C');
        $pdf->Ln(20);
        foreach ($facturacionMensual as $facturaMes) {
            $pdf->Cell(80,20,$facturaMes['Mes'],0,'C');
            $pdf->Cell(80,20,$facturaMes['Facturacion Total'],0,'C');
        }
       
        $pdf->Output('D', 'Facturacion-Mensual.pdf');
    }

    public function generarPDFParaFacturacionCliente($facturacionCliente){
        $pdf = new FPDF();
    
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',15);
        $pdf->Image('public/images/Cohete.png',80,8,33);
        $pdf->Ln(10);
        // Arial bold 15
 
        $pdf->Cell(80,20,'Facturacion Mensual',0,'C');
        $pdf->Ln(20);
        foreach ($facturacionCliente as $facturaCliente) {


            $pdf->Cell(80,20,$facturaCliente['id_usuario'],0,'C');
            $pdf->Cell(80,20,$facturaCliente['Facturacion Cliente'],0,'C');
            $pdf->Cell(80,20,$facturaCliente['nombre'],0,'C');
            $pdf->Cell(80,20,$facturaCliente['apellido'],0,'C');
            $pdf->Cell(80,20,$facturaCliente['dni'],0,'C');
        }
       
        $pdf->Output('D', 'Facturacion-Cliente.pdf');
    }



    public function generarPDFParaCabinaMasVendida($datosCabinaMasVendida){
        $pdf = new FPDF();
    
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',15);
        $pdf->Image('public/images/Cohete.png',80,8,33);
        $pdf->Ln(10);
        // Arial bold 15
 
        $pdf->Cell(80,20,'Cabina Mas Vendida',0,'C');
        $pdf->Ln(20);
        $pdf->Cell(80,20,$datosCabinaMasVendida['descripcion'],0,'C');
        $pdf->Cell(80,20,$datosCabinaMasVendida['Cantidad'],0,'C');
        $pdf->Output('D', 'Cabinas-Mas-Vendida.pdf');
    }
   
    

}