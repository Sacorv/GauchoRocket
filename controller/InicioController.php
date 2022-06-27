<?php

class InicioController {

    private  $printer;
   private $vueloModel;

    public function __construct($printer, $vueloModel) {

        $this->printer = $printer;
        $this->vueloModel=$vueloModel;
    }

    public function execute() {
        $data=array();

        $data['destinos']=$this->vueloModel->getDestinos();

        $data['origenes']=$this->vueloModel->getOrigenes();


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