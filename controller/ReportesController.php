
<?php
class ReportesController
{
    private $printer;
    private $reporteModel;
   
    public function __construct($printer,$reporteModel)
    {
        $this->printer = $printer;
        $this->reporteModel=$reporteModel;
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
          
          $this->printer->generateView('reporteView.html', $data);


    }
     

}


   