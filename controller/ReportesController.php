
<?php
class ReportesController
{
    private $printer;
    private $reporteModel;
    private $pdfHelper;

   
    public function __construct($printer,$reporteModel,$pdfHelper)
    {
        $this->printer = $printer;
        $this->reporteModel=$reporteModel;
        $this->pdfHelper=$pdfHelper;
    }
    public function execute() {
        $data=array();
        $cabinaMasVendida=$this->reporteModel->getCabinaMasVendida();
        $facturacionMensual=$this->reporteModel->getFacturacionMensual();
        $facturaCliente=$this->reporteModel->getFacturaCliente();
        $tasaOcupacion=$this->reporteModel->getTasaOcupacion();
        $data['descripcion']=$cabinaMasVendida['descripcion'];
        $data['cantidadDeCabinas']=$cabinaMasVendida['Cantidad'];
        $data['facturacion']=$facturacionMensual;
        $data['facturaCliente']=$facturaCliente;
        $data['tasaOcupacion']=$tasaOcupacion;
        
       
          if(isset($_SESSION["esCliente"]) && isset($_SESSION["logueado"]) && isset($_SESSION["logueado"])==1 ){
              $data["esCliente"]=true;

          }else if(isset($_SESSION["esAdmin"]) && isset($_SESSION["logueado"]) && isset($_SESSION["logueado"])==1 ){
              $data["esAdmin"]=true;

          }

          if(isset($_SESSION["nombre"])){
              $data["nombre"]=$_SESSION["nombre"];
          }
          /*Se genero el array, para separar la data correspondiente,y las etiquetas(Mes,Facturacion Total)
          para el grafico de Barras*/
          $facturacionMensualData=array();
          $facturacionMensualLabel=array();
          
          
          foreach($facturacionMensual as $facturaMes){
            array_push($facturacionMensualData,$facturaMes['Facturacion Total']);
            array_push( $facturacionMensualLabel,$facturaMes['Mes']);

          }
          //Convierto el Array en String para manipularlo con Javascript
          $data['FacturacionData']=implode(',',$facturacionMensualData);
          $data['FacturacionLabel']=implode(',',$facturacionMensualLabel);
          
          
          
          $tasaOcupacionData=array();
          $tasaOcupacionLabel=array();

          foreach($tasaOcupacion as $tasa){
            array_push($tasaOcupacionData,$tasa['Asientos Ocupados']);
            array_push($tasaOcupacionLabel,$tasa['id_viaje']);
           
          }
          $data['TasaOcupacionData']=implode(',',$tasaOcupacionData);
          $data['TasaOcupacionLabel']=implode(',',$tasaOcupacionLabel);


          $this->printer->generateView('reporteView.html', $data);


    }
     public function exportarPDF(){
        $tipoReporte=$_GET['tipoReporte'];

        if($tipoReporte == 'cabinaMasVendida'){

            $cabinaMasVendida=$this->reporteModel->getCabinaMasVendida();
            $this->pdfHelper->generarPDFParaCabinaMasVendida($cabinaMasVendida);
        }else if($tipoReporte == 'facturacionMensual'){
            $facturacionMensual=$this->reporteModel->getFacturacionMensual();
            $this->pdfHelper->generarPDFParaFacturacionMensual($facturacionMensual);
        }else if($tipoReporte == 'facturaCliente'){
            $facturaCliente=$this->reporteModel->getFacturaCliente();
            $this->pdfHelper->generarPDFParaFacturacionCliente($facturaCliente);

        }else if($tipoReporte == 'tasaDeOcupacion'){
            $tasaOcupacion=$this->reporteModel->getTasaOcupacion();
            $this->pdfHelper->generarPDFParaTasaDeOcupacionPorViaje($tasaOcupacion);

        }else{
            header("location: /reportes");
            exit();
        }
       
     }

}


   