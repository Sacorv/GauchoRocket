<?php

class VuelosController
{
      private VuelosModel $vuelosModel;
      private MustachePrinter $printer;

    public function __construct($vuelosModel, $printer) {
        $this->printer = $printer;
        $this->vuelosModel=$vuelosModel;
    }
  /*  public function execute(){
        $this->printer->generateView('/view/vuelosView.html');
    }*/
    public function validarVuelo(){
        //validar datos por post
        //obtener viajes del vueloModel
        $this->printer->generateView('vuelosView.html');
    }
}