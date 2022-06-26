<?php


class InicioController {

    private MustachePrinter $printer;


   private $vueloModel;

    public function __construct($printer, $vueloModel) {
        $this->printer = $printer;
        $this->vueloModel=$vueloModel;
    }

    public function execute() {
        $data=array();

        $data['destinos']=$this->vueloModel->getDestinos();

        $data['origenes']=$this->vueloModel->getOrigenes();

      if(isset($_SESSION["nombre"])){
          $data["nombre"]=$_SESSION["nombre"];
      }

      $this->printer->generateView("inicioView.html", $data);
    }



}