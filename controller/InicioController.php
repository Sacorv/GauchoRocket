<?php


class InicioController {
    private $printer;
   private $paisModel;

    public function __construct($printer,$paisModel) {
        $this->printer = $printer;
        $this->paisModel=$paisModel;
    }

    public function execute() {
      $destinos=$this->paisModel->getDestinos();
     
      $origenes=$this->paisModel->getOrigenes();

      $data=["origenes" => $origenes,"destinos"=>$destinos];
      $this->printer->generateView('inicioView.html',$data);
    }



}