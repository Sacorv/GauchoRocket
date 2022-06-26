<?php

class ReservaController
{
    private $reservaModel;
    private $printer;
    private $busquedaModel;

    public function __construct($reservaModel, $printer, $busquedaModel){
        $this->reservaModel= $reservaModel;
        $this->printer=$printer;
        $this->busquedaModel=$busquedaModel;
    }


    public function execute(){
        $nombreUsuario = $_SESSION["nombre"];
        $idUsuario = $_SESSION["id"];
        $datosUsuario = $this->reservaModel->buscarUsuario($idUsuario);


        $idVuelo = $_GET["vuelo"];
        $origen = $_GET["origen"];
        $destino = $_GET["destino"];

        $result = $this->reservaModel->buscarVuelo($idVuelo, $origen, $destino);

        $cabinas = $this->reservaModel->buscarCabinas($idVuelo, $origen, $destino);
        $servicios = $this->reservaModel->buscarServicio($idVuelo, $origen, $destino);
        $precioViaje = $this->reservaModel->calcularPrecioViaje($idVuelo, $origen, $destino);


        $data = ["vuelo"=>$result, "usuario"=>$datosUsuario, "nombre"=>$nombreUsuario, "cabinas"=>$cabinas, "servicios"=>$servicios, "precioViaje"=>$precioViaje];

        $this->printer->generateView('reservaView.html', $data);
    }

    public function reservarViaje()
    {
        $nombreUsuario = $_SESSION["nombre"];
        $idUsuario = $_SESSION["id"];

        $id_vuelo = $_POST["vuelo"];
        $id_origen = $_POST["origen"];
        $id_destino = $_POST["destino"];
        $fecha_partida = $_POST["fecha_partida"];
        $id_cabina = $_POST["cabina"];
        $id_servicio = $_POST["servicio"];
        $status_reserva = 1;


        $existReserva = $this->reservaModel->existeReserva($idUsuario, $id_vuelo);

        if($existReserva==null){
            $result = $this->reservaModel->registrarReserva($idUsuario, $id_vuelo, $id_origen, $fecha_partida, $id_destino, $id_cabina, $id_servicio, $status_reserva);

            if($result){
                header("Location: /reserva/misReservas");
            }
        }
        else{
            $error = "Usted ya ha reservado un pasaje para el vuelo seleccionado. Elija nuevamente.";

            $result = $this->busquedaModel->buscarDestinos($id_origen, $id_destino);
            $codigoViajero = $this->busquedaModel->buscarCodigoViajero($idUsuario);

            $data = ["encontrados" => $result, "codigo_viajero"=>$codigoViajero, "id_usuario"=>$idUsuario, "error"=>$error];
            $this->printer->generateView('destinosEncontradosView.html',$data);
        }
    }

    public function misReservas(){
        $id_usuario = $_SESSION["id"];
        $nombreUsuario = $_SESSION["nombre"];

        $result = $this->reservaModel->reservasDelUsuario($id_usuario);

        $data = ["reservas"=>$result, "nombre"=>$nombreUsuario];
        $this->printer->generateView('misReservasView.html', $data);
    }
}