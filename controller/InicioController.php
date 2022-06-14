<?php


class InicioController {

    private MustachePrinter $printer;


   private $paisModel;

    public function __construct($printer,$paisModel) {
        $this->printer = $printer;
        $this->paisModel=$paisModel;
    }

    public function execute() {

       $data=array();

      $data['destinos']=$this->paisModel->getDestinos();



     
      $data['origenes']=$this->paisModel->getOrigenes();

      if(isset($_SESSION["nombre"])){
          $data["nombre"]=$_SESSION["nombre"];
      }

      $this->printer->generateView("inicioView.html", $data);
    }



}