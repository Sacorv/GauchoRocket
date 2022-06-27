
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
        $data['id_cabina']=$cabinaMasVendida['id_cabina'];
        $data['cantidadDeCabinas']=$cabinaMasVendida['Cantidad'];
        
       
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


   