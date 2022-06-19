<?php

class BusquedaController
{

    private $busquedaModel;
    private $printer;

    public function __construct($busquedaModel, $printer)
    {
        $this->busquedaModel = $busquedaModel;
        $this->printer = $printer;
    }

    public function execute(){
        $origen = $_POST['origen'];
        $destino = $_POST['destino'];

        $result = $this->busquedaModel->buscarDestinos($origen, $destino);

        $data = ["encontrados" => $result];
        if($destino<=$origen){
            header("location: /");
            echo "<h3>No se encontraron vuelos disponibles.</h3>";
        }
        else{
            $this->printer->generateView('destinosEncontradosView.html',$data);
        }
    }

}