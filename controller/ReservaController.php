<?php

class ReservaController
{
    private $reservaModel;
    private $printer;
    private $busquedaModel;
    private $qrhelper;
    private $pdfhelper;

    public function __construct($reservaModel, $printer, $busquedaModel, $qrhelper, $pdfhelper ) {
        $this->reservaModel= $reservaModel;
        $this->printer=$printer;
        $this->busquedaModel=$busquedaModel;
        $this->qrhelper = $qrhelper;
        $this->pdfhelper = $pdfhelper;
    }


    public function execute(){
        if(!isset($_SESSION["logueado"]) && $_SESSION["logueado"]!=1 || isset($_SESSION["tipo"])!=2) {
            header("location: /");
            exit();
        }


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

    public function mediosDePago(){
        if(!isset($_SESSION["logueado"]) && $_SESSION["logueado"]!=1 || isset($_SESSION["tipo"])!=2) {
            header("location: /");
            exit();
        }

        $nombreUsuario = $_SESSION["nombre"];
        $idUsuario = $_SESSION["id"];
        $esCliente = $_SESSION["esCliente"];

        $id_tipo_viaje = $_POST["id_tipo_viaje"];

        $id_vuelo = $_POST["vuelo"];
        $id_origen = $_POST["id_origen"];
        $id_destino = $_POST["id_destino"];
        $origen = $_POST["origen"];
        $destino = $_POST["destino"];
        $fecha_partida = $_POST["fecha_partida"];
        $id_cabina = $_POST["cabina"];
        $id_servicio = $_POST["servicio"];
        $subtotal = $_POST["precio_subtotal"];

        $precioCabina = $this->reservaModel->precioCabinaPorId($id_cabina);
        $precioServicio = $this->reservaModel->precioServicioPorId($id_servicio);

        $total = $subtotal+$precioServicio[0]["precio"]+$precioCabina[0]["precio"];

        $data = ["subtotal"=>$subtotal, "precio_cabina"=>$precioCabina[0]["precio"], "precio_servicio"=>$precioServicio[0]["precio"],"total"=>$total,"id_tipo_viaje"=>$id_tipo_viaje, "id_vuelo"=>$id_vuelo, "id_origen"=>$id_origen, "origen"=>$origen, "destino"=>$destino ,"id_destino"=>$id_destino, "fecha_partida"=>$fecha_partida, "id_cabina"=>$id_cabina, "id_servicio"=>$id_servicio,"nombre"=>$nombreUsuario,"esCliente"=>$esCliente];

        $this->printer->generateView('mediosDePagoView.html', $data);

    }

    public function reservarViaje()
    {
        if(!isset($_SESSION["logueado"]) && $_SESSION["logueado"]!=1 || isset($_SESSION["tipo"])!=2) {
            header("location: /");
            exit();
        }

        $nombreUsuario = $_SESSION["nombre"];
        $idUsuario = $_SESSION["id"];
        $esCliente = $_SESSION["esCliente"];

        $id_tipo_viaje = $_POST["id_tipo_viaje"];


        $id_vuelo = $_POST["vuelo"];
        $id_origen = $_POST["origen"];
        $id_destino = $_POST["destino"];
        $fecha_partida = $_POST["fecha_partida"];
        $id_cabina = $_POST["cabina"];
        $id_servicio = $_POST["servicio"];


        $existReserva = $this->reservaModel->existeReserva($idUsuario, $id_vuelo);

        if($existReserva==null){
            $result = null;
            if($id_tipo_viaje!=""){
                $result = $this->reservaModel->registrarReserva($id_tipo_viaje, $idUsuario, $id_vuelo, $id_origen, $fecha_partida, $id_origen, $id_cabina, $id_servicio);
            }
            else{
                $result = $this->reservaModel->registrarReserva($id_tipo_viaje, $idUsuario, $id_vuelo, $id_origen, $fecha_partida, $id_destino, $id_cabina, $id_servicio);
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
        if(!isset($_SESSION["logueado"]) && $_SESSION["logueado"]!=1 || isset($_SESSION["tipo"])!=2) {
            header("location: /");
            exit();
        }

        $id_usuario = $_SESSION["id"];
        $nombreUsuario = $_SESSION["nombre"];
        $esCliente = $_SESSION["esCliente"];

        $result = $this->reservaModel->reservasDelUsuario($id_usuario);

        $data = null;

        if(count($result)!=0){
            $data = ["reservas"=>$result, "nombre"=>$nombreUsuario,"esCliente"=>$esCliente];
        }
        else{
            $error = 'No se encuentran reservas registradas actualmente';
            $data = ["reservas"=>$result, "nombre"=>$nombreUsuario,"esCliente"=>$esCliente, "error_mis_reservas"=>$error];
        }

        $this->printer->generateView('misReservasView.html', $data);
    }

    public function checkin (){
        $idReserva = $_GET["id"];

        $reserva = $this->reservaModel->buscarReserva($idReserva);

        $datosReserva = $this->reservaModel->buscarDatosDeReserva($reserva);

        $result = $this->reservaModel->confirmarReserva($idReserva);

        $this->qrhelper->generarCodigo($idReserva );
        $this->pdfhelper->generarPDF($idReserva , $datosReserva);


    }
}