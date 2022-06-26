<?php

class ReservaModel
{

    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function buscarUsuario($idUsuario){
        return $this->database->query("SELECT nombre, apellido, dni, email FROM usuario WHERE id='$idUsuario'");
    }

    public function buscarVuelo($idVuelo, $idDeOrigen, $idDestino){

        $query = $this->database->query("SELECT v.id, v.fecha_partida, l2.nombre AS origen, d.id_lugar AS id_destino, l.nombre AS escala, d.duracion_hs AS horas, m.cabina_t AS cabina_turista, m.cabina_e AS cabina_ejecutivo, m.cabina_p AS cabina_primera, d.orden, v.duracion_total AS total_horas, tv.descripcion AS tipo_viaje, e.matricula, m.nombre AS modelo, m.tipo_equipo, te.nombre AS categoria_equipo 
                                            FROM viaje v
                                            JOIN tipo_viaje tv ON tv.id=v.id_tipo_viaje
                                            JOIN duracion d ON d.id_tipo_viaje=tv.id
                                            JOIN equipo e ON e.id=v.id_equipo
                                            JOIN modelo m ON m.id=e.tipo_modelo
                                            JOIN tipo_equipo te ON te.id=m.tipo_equipo
                                            JOIN lugar l ON l.id=d.id_lugar
                                            JOIN lugar l2 ON l2.id=v.id_lugar_origen
                                            WHERE d.id_lugar<='$idDestino' AND d.id_tipo_equipo=te.id AND v.id='$idVuelo'
                                            ORDER BY d.orden");

        $escalas = [];
        $escala = null;
        $vuelo = [];

        for ($i = 0; $i < count($query); $i++) {
            if ($i == 0) {
                $hours = $query[$i]["horas"];
                $date = $query[$i]["fecha_partida"];
                $llegada = date('Y-m-d H:i', strtotime($date . ' + ' . $hours . 'hours'));

                $escala = ["id_destino" => $query[$i]["id_destino"], "destino" => $query[$i]["escala"], "fecha_llegada" => $llegada];
                $escalas[] = $escala;
            } else {
                $hours = $query[$i]["horas"];
                $date = $escalas[($i - 1)]["fecha_llegada"];
                $llegada = date('Y-m-d H:i', strtotime($date . ' + ' . $hours . 'hours'));

                $escala = ["id_destino" => $query[$i]["id_destino"], "destino" => $query[$i]["escala"], "fecha_llegada" => $llegada];
                $escalas[] = $escala;
            }

        }

        $destino = $this->database->query("SELECT nombre from lugar where id='$idDestino'");

        if ($idDeOrigen == 1 or $idDeOrigen == 2) {

            $vuelo = ["id_vuelo" => $idVuelo, "fecha_partida" => $query[0]["fecha_partida"], "id_origen" => $idDeOrigen, "origen" => $query[0]["origen"], "escala" => $escalas, "id_destino" => $idDestino, "destino" => $destino[0]["nombre"], "cabina_turista"=>$query[0]["cabina_turista"], "cabina_ejecutivo"=>$query[0]["cabina_ejecutivo"], "cabina_primera"=>$query[0]["cabina_primera"]];

        } else {
            $escalaDiferenteOrigen = [];
            $fechaSalida = null;
            $origen = null;
            $id_origen = null;

            for ($i = 0; $i < count($escalas); $i++) {
                if ($escalas[$i]["id_destino"] == $idDeOrigen) {
                    $fechaSalida = $escalas[$i]["fecha_llegada"];
                    $origen = $escalas[$i]["destino"];
                    $id_origen = $escalas[$i]["id_destino"];
                } else {
                    if ($escalas[$i]["id_destino"] > $idDeOrigen) {
                        $escalaDiferenteOrigen[] = $escalas[$i];
                    }
                }
            }

            $vuelo = ["id_vuelo" => $idVuelo, "fecha_partida" => $fechaSalida, "id_origen" => $id_origen, "origen" => $origen, "escala" => $escalaDiferenteOrigen, "id_destino" => $idDestino, "destino" => $destino[0]["nombre"], "cabina_turista"=>$query[0]["cabina_turista"], "cabina_ejecutivo"=>$query[0]["cabina_ejecutivo"], "cabina_primera"=>$query[0]["cabina_primera"]];

        }
        return $vuelo;
    }

    public function buscarCabinas($idVuelo, $origen, $destino){
        $cabinas = $this->database->query("SELECT * from cabina");

        $datosVuelo = $this->buscarVuelo($idVuelo, $origen, $destino);

        $result = array();

        foreach ($cabinas as $cabina){
            if($datosVuelo["cabina_turista"]>0){
                if($cabina["descripcion"] == "Turista")
                    $result[]= ["id"=>$cabina["id"], "descripcion"=>$cabina["descripcion"], "precio"=>$cabina["precio"]];
            }
            if($datosVuelo["cabina_ejecutivo"]>0){
                if($cabina["descripcion"] == "Ejecutivo")
                    $result[]= ["id"=>$cabina["id"], "descripcion"=>$cabina["descripcion"], "precio"=>$cabina["precio"]];
            }
            if($datosVuelo["cabina_primera"]>0){
                if($cabina["descripcion"] == "Primera")
                    $result[]= ["id"=>$cabina["id"], "descripcion"=>$cabina["descripcion"], "precio"=>$cabina["precio"]];
            }
        }
        return $result;
    }

    public function buscarServicio($idVuelo, $origen, $destino){
        return $this->database->query("SELECT * from servicio");
    }

    public function calcularPrecioViaje($idVuelo, $origen, $destino){
        $precioPorTramo = 400;

        $datosVuelo = $this->buscarVuelo($idVuelo, $origen, $destino);

        $cantidadTramos = sizeof($datosVuelo["escala"]);

        return $precioPorTramo*$cantidadTramos;
    }

    public function registrarReserva($idUsuario, $id_vuelo, $id_origen, $fecha_partida, $id_destino, $id_cabina, $id_servicio, $status_reserva){

        $precio_tramo = $this->calcularPrecioViaje($id_vuelo, $id_origen, $id_destino);
        $queryCabina = $this->database->query("SELECT precio FROM cabina WHERE id='$id_cabina'");
        $queryServicio = $this->database->query("SELECT precio FROM servicio WHERE id='$id_servicio'");

        $precioCabina = $queryCabina[0]["precio"];
        $precioServicio = $queryServicio[0]["precio"];


        return $this->database->createReserva($idUsuario, $id_vuelo, $id_origen, $fecha_partida, $id_destino, $id_cabina, $id_servicio, $status_reserva, $precio_tramo, $precioCabina, $precioServicio);
    }

    public function existeReserva($idUsuario, $id_vuelo){
        return $this->database->query("SELECT * FROM reserva WHERE id_viaje='$id_vuelo' AND id_usuario='$idUsuario'");
    }

    public function buscarDetalleReserva($id_viaje, $id_usuario){

        return $this->database->query("SELECT r.id_viaje, r.fecha_partida, l.nombre AS origen, l2.nombre AS destino, c.descripcion AS cabina, s.descripcion AS servicio, sr.descripcion AS 'Estado de reserva', r.subtotal_tramos, r.precio_cabina, r.precio_servicio, r.checkin, r.id_usuario, u.nombre, u.apellido, u.dni, v.id_equipo, e.matricula, m.nombre
                                FROM reserva r
                                JOIN usuario u ON u.id=r.id_usuario
                                JOIN viaje v ON v.id=r.id_viaje
                                JOIN lugar l ON l.id=r.id_origen
                                JOIN lugar l2 ON l2.id=r.id_destino
                                JOIN equipo e ON e.id=v.id_equipo
                                JOIN cabina c ON c.id=r.id_cabina
                                JOIN servicio s ON s.id=r.id_servicio
                                JOIN status_reserva sr ON sr.id=r.id_status_reserva
                                JOIN modelo m ON m.id=e.tipo_modelo
                                WHERE r.id_usuario='$id_usuario' AND r.id_viaje='$id_viaje'");
    }

    public function reservasDelUsuario($id_usuario){
        $query = $this->database->query("SELECT r.id_viaje, r.fecha_partida, l.nombre AS origen, l2.nombre AS destino, c.descripcion AS cabina, s.descripcion AS servicio, sr.descripcion AS 'Estado de reserva', r.subtotal_tramos, r.precio_cabina, r.precio_servicio, r.checkin, r.id_usuario, u.nombre, u.apellido, u.dni, v.id_equipo, e.matricula, m.nombre
                                FROM reserva r
                                JOIN usuario u ON u.id=r.id_usuario
                                JOIN viaje v ON v.id=r.id_viaje
                                JOIN lugar l ON l.id=r.id_origen
                                JOIN lugar l2 ON l2.id=r.id_destino
                                JOIN equipo e ON e.id=v.id_equipo
                                JOIN cabina c ON c.id=r.id_cabina
                                JOIN servicio s ON s.id=r.id_servicio
                                JOIN status_reserva sr ON sr.id=r.id_status_reserva
                                JOIN modelo m ON m.id=e.tipo_modelo
                                WHERE r.id_usuario='$id_usuario'
                                ORDER BY fecha_partida");

        $reservas = [];

        foreach ($query as $reserva){
            $total = $reserva["subtotal_tramos"]+ $reserva["precio_cabina"] + $reserva["precio_servicio"];

            $reserva["total"] = $total;
            $reservas[] = $reserva;
        }
        return $reservas;
    }

}