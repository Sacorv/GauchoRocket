<?php

class TurnosController
{
    private MustachePrinter $printer;
    private UserModel $userModel;
    private $turnoSolicitado;

    public function __construct($userModel,$printer) {
        $this->printer = $printer;
        $this->userModel=$userModel;
    $this->turnoSolicitado=false;
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
        $data["turnoSolicitado"]=$this->turnoSolicitado;
        $this->printer->generateView("turnosView.html",$data);
    }
    
    public function realizarChequeo(){
        
        if(isset($_SESSION["codigo_viajero"]) && $_SESSION["codigo_viajero"]=="0"){
        
            $resultado=rand(1,3);
            $this->userModel->updateCodigoViajero($resultado);
            $_SESSION["codigo_viajero"]=$resultado;

            $this->turnoSolicitado=true;
            header("location:/turnos");
          
            exit();

        }else{
            header("location:/turnos/errorAlSolicitarTurno");
          
            exit();
         
          
        }
        
    }
    public function errorAlSolicitarTurno(){
        $data=array();
        if(!isset($_SESSION["logueado"]) && $_SESSION["logueado"]!=1 || $_SESSION["tipo"]!=2) {
            header("location: /");
            exit();
        }

        if(isset($_SESSION["nombre"])){
            $data["nombre"] = $_SESSION["nombre"];
        }
        $this->printer->generateView("errorAlSolicitarTurno.html",$data);
    }



}