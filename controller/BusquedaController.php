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

        if($destino<=$origen){
            header("location: /");
            echo "<h3>No se encontraron vuelos disponibles.</h3>";
            exit();
        }

        if(isset($_SESSION["id"])){
            $nombreUsuario = $_SESSION["nombre"];
            $idUsuario = $_SESSION["id"];
            $codigoViajero = $this->busquedaModel->buscarCodigoViajero($idUsuario);
            $esCliente = isset($_SESSION["esCliente"]) ?? $_SESSION["esCliente"];
            $esAdmin = isset($_SESSION["esAdmin"]) ?? $_SESSION["esAdmin"];

            if($codigoViajero[0]["codigo_viajero"]!=0){
                $data = ["encontrados" => $result, "codigo_viajero"=>$codigoViajero, "id_usuario"=>$_SESSION["id"], "nombre"=>$nombreUsuario,"esCliente"=>$esCliente, "esAdmin"=>$esAdmin];
            }
            else{
                $data = ["encontrados" => $result,"nombre"=>$nombreUsuario,"esCliente"=>$esCliente,"esAdmin"=>$esAdmin];
            }
        }
        else{
            $data = ["encontrados" => $result];
        }


        $this->printer->generateView('destinosEncontradosView.html',$data);
    }


    public function suborbitales(){
        $idSuborbital = 4;
        $result = $this->busquedaModel->buscarViajes($idSuborbital);

        if(isset($_SESSION["id"])) {
            $nombreUsuario = $_SESSION["nombre"];
            $idUsuario = $_SESSION["id"];
            $codigoViajero = $this->busquedaModel->buscarCodigoViajero($idUsuario);
            $esCliente = isset($_SESSION["esCliente"]) ?? $_SESSION["esCliente"];
            $esAdmin = isset($_SESSION["esAdmin"]) ?? $_SESSION["esAdmin"];

            if ($codigoViajero[0]["codigo_viajero"] != 0) {
                $data = ["viajes" => $result, "idSuborbital" => $idSuborbital, "codigo_viajero"=>$codigoViajero, "nombre"=>$nombreUsuario, "esCliente"=>$esCliente,"esAdmin"=>$esAdmin];
            }
            else{
                $data = ["viajes" => $result, "idSuborbital" => $idSuborbital,"nombre"=>$nombreUsuario,"esCliente"=>$esCliente,"esAdmin"=>$esAdmin];
            }
        }
        else{
            $data = ["viajes" => $result, "idSuborbital" => $idSuborbital];
        }
        $this->printer->generateView('suborbitalesYToursView.html', $data);
    }

    public function tours(){
        $idTour = 3;
        $result = $this->busquedaModel->buscarViajes($idTour);

        if(isset($_SESSION["id"])) {
            $nombreUsuario = $_SESSION["nombre"];
            $idUsuario = $_SESSION["id"];
            $codigoViajero = $this->busquedaModel->buscarCodigoViajero($idUsuario);
            $esCliente = isset($_SESSION["esCliente"]) ?? $_SESSION["esCliente"];
            $esAdmin = isset($_SESSION["esAdmin"]) ?? $_SESSION["esAdmin"];

            if ($codigoViajero[0]["codigo_viajero"] != 0) {
                $data = ["viajes" => $result, "idTour"=>$idTour, "codigo_viajero"=>$codigoViajero,"nombre"=>$nombreUsuario,"esCliente"=>$esCliente,"esAdmin"=>$esAdmin];
            }
            else{
                $data = ["viajes" => $result, "idTour"=>$idTour,"nombre"=>$nombreUsuario,"esCliente"=>$esCliente,"esAdmin"=>$esAdmin];
            }
        }
        else{
            $data = ["viajes" => $result, "idTour"=>$idTour];
        }
        $this->printer->generateView('suborbitalesYToursView.html', $data);
    }

}