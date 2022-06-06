<?php


class InicioController {
    private $printer;
   // private $viajeModel;
   private $paisModel;

    public function __construct($ViajeModel, $printer,$paisModel) {
        $this->viajeModel = $ViajeModel;
        $this->printer = $printer;
        $this->paisModel=$paisModel;
    }

    public function execute() {
        //$vuelos  = $this->viajeModel->getVuelos();
      //  $data = ["vuelos" => $vuelos];
      $destinos=$this->paisModel->getDestinos();
     
      $origenes=$this->paisModel->getOrigenes();

      $data=["origenes" => $origenes,"destinos"=>$destinos];
      $this->printer->generateView('inicioView.html',$data);
    }



}