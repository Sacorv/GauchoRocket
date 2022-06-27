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
        $esCliente = $_SESSION["esCliente"];

        $idVuelo = $_GET["vuelo"];
        $origen = $_GET["origen"];
        $destino = $_GET["destino"];


        $result = null;
        if($destino != ""){
            $result = $this->reservaModel->buscarVuelo($idVuelo, $origen, $destino);

            $cabinas = $this->reservaModel->buscarCabinas($idVuelo, $origen, $destino);
            $servicios = $this->reservaModel->buscarServicio();
            $precioViaje = $this->reservaModel->calcularPrecioViaje($idVuelo, $origen, $destino);
        }
        else{
            $result = $this->reservaModel->buscarVueloPorId($idVuelo);

            $cabinas = $this->reservaModel->buscarCabinasPorIdVuelo($idVuelo);
            $servicios = $this->reservaModel->buscarServicio();
            $precioViaje = $this->reservaModel->calcularPrecioViajePorIdVuelo($idVuelo);
        }


        $data = ["vuelo"=>$result, "usuario"=>$datosUsuario, "nombre"=>$nombreUsuario, "cabinas"=>$cabinas, "servicios"=>$servicios, "precioViaje"=>$precioViaje,"esCliente"=>$esCliente];

        $this->printer->generateView('reservaView.html', $data);
    }

    public function reservarViaje()
    {
        $nombreUsuario = $_SESSION["nombre"];
        $idUsuario = $_SESSION["id"];
        $esCliente = $_SESSION["esCliente"];

        $id_tipo_viaje = $_POST["id_tipo_viaje"];

        var_dump($id_tipo_viaje);
        $id_vuelo = $_POST["vuelo"];
        $id_origen = $_POST["origen"];
        $id_destino = $_POST["destino"];
        $fecha_partida = $_POST["fecha_partida"];
        $id_cabina = $_POST["cabina"];
        $id_servicio = $_POST["servicio"];
        $status_reserva = 1;

        $existReserva = $this->reservaModel->existeReserva($idUsuario, $id_vuelo);

        if($existReserva==null){
            $result = null;
            if($id_tipo_viaje!=""){
                $result = $this->reservaModel->registrarReserva($id_tipo_viaje, $idUsuario, $id_vuelo, $id_origen, $fecha_partida, $id_origen, $id_cabina, $id_servicio, $status_reserva);
            }
            else{
                $result = $this->reservaModel->registrarReserva($id_tipo_viaje, $idUsuario, $id_vuelo, $id_origen, $fecha_partida, $id_destino, $id_cabina, $id_servicio, $status_reserva);
            }

            if($result){
                header("Location: /reserva/misReservas");
            }
        }
        else{
            $error = "Usted ya ha reservado un pasaje para el vuelo seleccionado. Elija nuevamente.";

            $codigoViajero = $this->busquedaModel->buscarCodigoViajero($idUsuario);
            $result = null;
            if($id_destino!=""){
                $result = $this->busquedaModel->buscarDestinos($id_origen, $id_destino);
                $data = ["encontrados" => $result, "codigo_viajero"=>$codigoViajero, "id_usuario"=>$idUsuario, "error"=>$error,"esCliente"=>$esCliente];
                $this->printer->generateView('destinosEncontradosView.html',$data);
            }
            else{
                $result = $this->busquedaModel->buscarViajes($id_tipo_viaje);
                $tipoViaje = null;
                if($id_tipo_viaje==3){
                    $tipoViaje = "idTour";
                }
                else{
                    $tipoViaje = "idSuborbital";
                }
                $data = ["viajes" => $result, "error"=>$error, $tipoViaje=>$id_tipo_viaje, "codigo_viajero"=>$codigoViajero,"nombre"=>$nombreUsuario,"esCliente"=>$esCliente];
                $this->printer->generateView('suborbitalesYToursView.html', $data);
            }

        }
    }

    public function misReservas(){
        $id_usuario = $_SESSION["id"];
        $nombreUsuario = $_SESSION["nombre"];
        $esCliente = $_SESSION["esCliente"];

        $result = $this->reservaModel->reservasDelUsuario($id_usuario);

        $data = ["reservas"=>$result, "nombre"=>$nombreUsuario,"esCliente"=>$esCliente];
        $this->printer->generateView('misReservasView.html', $data);
    }
}