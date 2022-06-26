<?php

class TurnosController
{
    private MustachePrinter $printer;


    public function __construct($printer) {
        $this->printer = $printer;

    }
    public function execute(){
        $data=array();

        if(!isset($_SESSION["logueado"]) && $_SESSION["logueado"]!=1 || isset($_SESSION["tipo"])!=2) {
            header("location: /");
            exit();
        }

        if(isset($_SESSION["esCliente"]) && $_SESSION["esCliente"]){
            $data["esCliente"]=true;
        }

        if(isset($_SESSION["nombre"])){
            $data["nombre"] = $_SESSION["nombre"];
        }
        $this->printer->generateView("turnosView.html",$data);
    }
}