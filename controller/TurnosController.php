<?php

class TurnosController
{
    private MustachePrinter $printer;
    private UserModel $userModel;

    public function __construct($userModel,$printer) {
        $this->printer = $printer;
        $this->userModel=$userModel;
    }
    public function execute(){
        $data=array();
        if(!isset($_SESSION["logueado"]) && $_SESSION["logueado"]!=1 || $_SESSION["tipo"]!=2) {
            header("location: /");
            exit();
        }

    /*    if(isset($_SESSION["esCliente"]) && $_SESSION["esCliente"]){
            $data["esCliente"]=true;
        }
*/
        if(isset($_SESSION["nombre"])){
            $data["nombre"] = $_SESSION["nombre"];
        }
        $this->printer->generateView("turnosView.html",$data);
    }
    
    public function realizarChequeo(){
        
        $resultado=rand(1,3);
        $this->userModel->updateCodigoViajero($resultado);
        header("location:/turnos");
        
        exit();
    }



}