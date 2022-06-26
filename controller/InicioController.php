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


          if(isset($_SESSION["esCliente"]) && isset($_SESSION["logueado"]) && isset($_SESSION["logueado"])==1 ){
              $data["esCliente"]=true;

          }else if(isset($_SESSION["esAdmin"]) && isset($_SESSION["logueado"]) && isset($_SESSION["logueado"])==1 ){
              $data["esAdmin"]=true;

          }

          if(isset($_SESSION["nombre"])){
              $data["nombre"]=$_SESSION["nombre"];
          }
          $this->printer->generateView("inicioView.html", $data);


    }



}