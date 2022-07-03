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
        $pdf->Ln(40);
        // Arial bold 15
        $pdf->Cell(0,20,'Tasa De Ocupacion',0,0,'C');
        $header=['Viaje','Asientos Ocupados','Asientos Totales','Tasa'];
       
    
        $pdf->Ln();
        $w = array(25, 55, 50, 30);
      
        for($i=0;$i<count($header);$i++){
            $pdf->Cell($w[$i],7,$header[$i],1);
        }
        $pdf->Ln();
      
    // Datos
    foreach($tasaDeOcupacion as $row)
    {
        $pdf->Cell($w[0],7,$row["id_viaje"],1);
        $pdf->Cell($w[1],7,$row["Asientos Ocupados"],1);
        $pdf->Cell($w[2],7,$row["Asientos Totales"],1);
        $pdf->Cell($w[3],7,$row["Tasa"],1);
        $pdf->Ln();
       }
        $pdf->Output('D', 'Tasa-De-Ocupacion-Por-Viaje.pdf');
    }



    
    public function generarPDFParaFacturacionMensual($facturacionMensual){
        $pdf = new FPDF();
    
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',15);
        $pdf->Image('public/images/Cohete.png',80,8,33);
        $pdf->Ln(40);
        // Arial bold 15
        $pdf->Cell(0,20,'Facturacion Mensual',0,0,'C');
        $header=['Mes','Facturacion Total'];
        
        $pdf->Ln();
        $w = array(25, 55, 50, 30);

        for($i=0;$i<count($header);$i++){
            $pdf->Cell($w[$i],7,$header[$i],1);
        }
        $pdf->Ln();

        foreach ($facturacionMensual as $facturaMes) {
            
            $pdf->Cell($w[0],7,$facturaMes["Mes"],1);
            $pdf->Cell($w[1],7,$facturaMes["Facturacion Total"],1);
            $pdf->Ln();
        }
       
        $pdf->Output('D', 'Facturacion-Mensual.pdf');
    }

    public function generarPDFParaFacturacionCliente($facturacionCliente){
        $pdf = new FPDF();
    
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',15);
        $pdf->Image('public/images/Cohete.png',80,8,33);
        $pdf->Ln(40);
        // Arial bold 15
 
        $pdf->Cell(0,20,'Facturacion Por Cliente',0,0,'C');
        $pdf->Ln(20);
        $w = array(30, 55, 30, 40,25);
        
         
        $header=['Id_Usuario','Facturacion Cliente','Nombre','Apellido','DNI'];
        for($i=0;$i<count($header);$i++){
            $pdf->Cell($w[$i],7,$header[$i],1);
        }
        $pdf->Ln();
    // Datos
    
    foreach($facturacionCliente as $row)
    {
        $pdf->Cell($w[0],7,$row["id_usuario"],1);

        $pdf->Cell($w[1],7,$row["Facturacion Cliente"],1);
        $pdf->Cell($w[2],7,$row["nombre"],1);
        $pdf->Cell($w[3],7,$row["apellido"],1);
        $pdf->Cell($w[4],7,$row["dni"],1);
        $pdf->Ln();
    }
      
        $pdf->Output('D', 'Facturacion-Cliente.pdf');
    }



    public function generarPDFParaCabinaMasVendida($datosCabinaMasVendida){
        $pdf = new FPDF();
    
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',15);
        $pdf->Image('public/images/Cohete.png',80,8,33);
        $pdf->Ln(40);
        // Arial bold 15
 
        $pdf->Cell(0,20,'Cabina Mas Vendida:',0,0,'C');
        $pdf->Ln(20);
        $pdf->Cell(80,20,$datosCabinaMasVendida['descripcion'].' con '.$datosCabinaMasVendida['Cantidad'].
         ' reservas',0,'C');
        $pdf->Output('D', 'Cabinas-Mas-Vendida.pdf');
    }
   
    

}