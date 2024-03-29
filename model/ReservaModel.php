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

            // Calcular disponibilidad por tipo de cabina
            $lugaresDisponiblesCabinas = $this->buscarLugaresDisponiblesCabinaPorOrigenYDestino($idDeOrigen, $idDestino, $idVuelo);

            $disponibleEjecutivo = false;
            $disponiblePrimera = false;
            $disponibleTurista = false;

            if($lugaresDisponiblesCabinas["ejecutivo"]<=0){
                $disponibleEjecutivo = 'Agotado';
            }
            if($lugaresDisponiblesCabinas["primera"]<=0){
                $disponiblePrimera = 'Agotado';
            }
            if($lugaresDisponiblesCabinas["turista"]<=0){
                $disponibleTurista = 'Agotado';
            }

            $vuelo = ["id_vuelo" => $idVuelo, "fecha_partida" => $query[0]["fecha_partida"], "id_origen" => $idDeOrigen, "origen" => $query[0]["origen"], "escala" => $escalas, "id_destino" => $idDestino, "destino" => $destino[0]["nombre"], "cabina_turista"=>$query[0]["cabina_turista"], "cabina_ejecutivo"=>$query[0]["cabina_ejecutivo"], "cabina_primera"=>$query[0]["cabina_primera"], "disponible_turista"=>$disponibleTurista, "disponible_ejecutivo"=>$disponibleEjecutivo, "disponible_primera"=>$disponiblePrimera];


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

            // Calcular disponibilidad por tipo de cabina
            $lugaresDisponiblesCabinas = $this->buscarLugaresDisponiblesCabinaPorOrigenYDestino($id_origen, $idDestino, $idVuelo);

            $disponibleEjecutivo = false;
            $disponiblePrimera = false;
            $disponibleTurista = false;

            if($lugaresDisponiblesCabinas["ejecutivo"]<=0){
                $disponibleEjecutivo = 'Agotado';
            }
            if($lugaresDisponiblesCabinas["primera"]<=0){
                $disponiblePrimera = 'Agotado';
            }
            if($lugaresDisponiblesCabinas["turista"]<=0){
                $disponibleTurista = 'Agotado';
            }

            $vuelo = ["id_vuelo" => $idVuelo, "fecha_partida" => $fechaSalida, "id_origen" => $id_origen, "origen" => $origen, "escala" => $escalaDiferenteOrigen, "id_destino" => $idDestino, "destino" => $destino[0]["nombre"], "cabina_turista"=>$query[0]["cabina_turista"], "cabina_ejecutivo"=>$query[0]["cabina_ejecutivo"], "cabina_primera"=>$query[0]["cabina_primera"], "disponible_turista"=>$disponibleTurista, "disponible_ejecutivo"=>$disponibleEjecutivo, "disponible_primera"=>$disponiblePrimera];


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
                    $result[]= ["id"=>$cabina["id"], "descripcion"=>$cabina["descripcion"], "precio"=>$cabina["precio"], "disponible"=>$datosVuelo["disponible_turista"]];
            }
            if($datosVuelo["cabina_ejecutivo"]>0){
                if($cabina["descripcion"] == "Ejecutivo")
                    $result[]= ["id"=>$cabina["id"], "descripcion"=>$cabina["descripcion"], "precio"=>$cabina["precio"], "disponible"=>$datosVuelo["disponible_ejecutivo"]];
            }
            if($datosVuelo["cabina_primera"]>0){
                if($cabina["descripcion"] == "Primera")
                    $result[]= ["id"=>$cabina["id"], "descripcion"=>$cabina["descripcion"], "precio"=>$cabina["precio"], "disponible"=>$datosVuelo["disponible_primera"]];
            }
        }
        return $result;
    }

    public function buscarServicio(){
        return $this->database->query("SELECT * from servicio");
    }

    public function calcularPrecioViaje($idVuelo, $origen, $destino){
        $precioPorTramo = 400;

        $datosVuelo = $this->buscarVuelo($idVuelo, $origen, $destino);

        $cantidadTramos = sizeof($datosVuelo["escala"]);

        return $precioPorTramo*$cantidadTramos;
    }

    public function buscarLugaresDisponiblesCabinaPorOrigenYDestino($idDeOrigen, $idDestino, $idVuelo){
        $capacidadCabinas = $this->database->query("SELECT m.cabina_t AS Turista, m.cabina_e AS Ejecutivo, m.cabina_p AS Primera
                                                    FROM viaje v
                                                    JOIN tipo_viaje tv ON tv.id=v.id_tipo_viaje
                                                    JOIN duracion d ON d.id_tipo_viaje=tv.id
                                                    JOIN equipo e ON e.id=v.id_equipo
                                                    JOIN modelo m ON m.id=e.tipo_modelo
                                                    JOIN tipo_equipo te ON te.id=m.tipo_equipo
                                                    JOIN lugar l ON l.id=d.id_lugar
                                                    JOIN lugar l2 ON l2.id=v.id_lugar_origen
                                                    WHERE d.id_tipo_equipo=te.id AND v.id='$idVuelo'
                                                    GROUP BY m.cabina_t");


        $reservasCabinas = $this->database->query("SELECT c.id, c.descripcion, r.id_origen, r.id_destino 
                                                    FROM reserva r
                                                    JOIN cabina c ON c.id=r.id_cabina
                                                    WHERE id_viaje = '$idVuelo'");

        $cantidadOcupadasEjecutivo = 0;
        $cantidadOcupadasPrimera = 0;
        $cantidadOcupadasTurista = 0;

        for($i=0; $i<sizeof($reservasCabinas); $i++){
            if($reservasCabinas[$i]["descripcion"] == 'Ejecutivo'){
                if($reservasCabinas[$i]["id_destino"]>$idDeOrigen and $reservasCabinas[$i]["id_origen"]<$idDestino) {
                    $cantidadOcupadasEjecutivo++;
                }
            }
            if($reservasCabinas[$i]["descripcion"] == 'Primera'){
                if($reservasCabinas[$i]["id_destino"]>$idDeOrigen and $reservasCabinas[$i]["id_origen"]<$idDestino) {
                    $cantidadOcupadasPrimera++;
                }
            }
            if($reservasCabinas[$i]["descripcion"] == 'Turista'){
                if($reservasCabinas[$i]["id_destino"]>$idDeOrigen and $reservasCabinas[$i]["id_origen"]<$idDestino) {
                    $cantidadOcupadasTurista++;
                }
            }
        }

        $disponiblesEjecutivo = $capacidadCabinas[0]["Ejecutivo"] - $cantidadOcupadasEjecutivo;
        $disponiblesPrimera = $capacidadCabinas[0]["Primera"] - $cantidadOcupadasPrimera;
        $disponiblesTurista = $capacidadCabinas[0]["Turista"] - $cantidadOcupadasTurista;

        $lugaresDisponiblesCabinas = ["ejecutivo"=>$disponiblesEjecutivo, "primera"=>$disponiblesPrimera, "turista"=>$disponiblesTurista];

        return $lugaresDisponiblesCabinas;
    }


    public function registrarReserva($id_tipo_viaje, $idUsuario, $id_vuelo, $id_origen, $fecha_partida, $id_destino, $id_cabina, $id_servicio){
        $precio_tramo = null;

        //suborbital o tour
        if($id_tipo_viaje!=""){
            $precio_tramo = $this->calcularPrecioViajePorIdVuelo($id_vuelo);
        }
        else{
            $precio_tramo = $this->calcularPrecioViaje($id_vuelo, $id_origen, $id_destino);
        }
        $queryCabina = $this->database->query("SELECT precio FROM cabina WHERE id='$id_cabina'");
        $queryServicio = $this->database->query("SELECT precio FROM servicio WHERE id='$id_servicio'");

        $precioCabina = $queryCabina[0]["precio"];
        $precioServicio = $queryServicio[0]["precio"];

        //Validación de fecha reserva vs fecha de salida viaje
        $status_reserva = $this->determinarEstadoReservaSegunFecha($fecha_partida);

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

        $query = $this->database->query("SELECT r.id , v.id_tipo_viaje,
                                               tv.descripcion AS tipo_viaje, r.id_viaje, r.fecha_partida, l.nombre AS origen, l2.nombre AS destino, 
                                               c.descripcion AS cabina, s.descripcion AS servicio, sr.descripcion AS 'Estado de reserva', 
                                               r.subtotal_tramos, r.precio_cabina, r.precio_servicio, r.checkin, r.id_usuario, u.nombre, u.apellido, 
                                               u.dni, v.id_equipo, e.matricula, m.nombre
                                        FROM reserva r
                                        JOIN usuario u ON u.id=r.id_usuario
                                        JOIN viaje v ON v.id=r.id_viaje
                                        JOIN tipo_viaje tv ON tv.id=v.id_tipo_viaje
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

    public function confirmarReserva ( $idReserva ){
        $result = $this->database->confirmarReserva($idReserva);
        return $result;
    }
    public function buscarReserva ($id_reserva){
        $query = $this->database->query( 'select * from reserva WHERE id = '.$id_reserva);

        $result = [];

        foreach ($query as $resultado){
            $total = $resultado["subtotal_tramos"]+ $resultado["precio_cabina"] + $resultado["precio_servicio"];

            $result['id'] = $resultado["id"];
            $result['id_viaje'] = $resultado["id_viaje"];
            $result['fecha_partida'] = $resultado["fecha_partida"];
            $result['id_origen'] = $resultado["id_origen"];
            $result['id_destino'] = $resultado["id_destino"];
            $result['id_usuario'] = $resultado["id_usuario"];
            $result['id_cabina'] = $resultado["id_cabina"];
            $result['id_servicio'] = $resultado["id_servicio"];
            $result['total'] = $total;
        }
        return $result;

    }
    public function buscarDatosDeReserva ($reserva)
    {
        $idReserva = $reserva["id"];
        $idViaje = $reserva["id_viaje"];
        $fechaPartida = $reserva["fecha_partida"];
        $idOrigen = $reserva["id_origen"];
        $idDestino = $reserva["id_destino"];
        $idUsuario = $reserva["id_usuario"];
        $idCabina = $reserva["id_cabina"];
        $idServicio = $reserva["id_servicio"];
        $total = $reserva['total'];

        $query = $this->database->query('select us.nombre, us.apellido, us.dni , orig.nombre as "Origen" , 
                                            dest.nombre as "Destino" , t_viaje.descripcion as "Tipo de viaje",
                                             cab.descripcion as "Cabina", serv.descripcion as "Servicio" 
                                                    from Reserva res 
                                                    JOIN usuario us on us.id =' . $idUsuario . '   
                                                    JOIN viaje v on v.id  = ' . $idViaje . '  
                                                    JOIN tipo_viaje t_viaje on v.id_tipo_viaje = t_viaje.id 
                                                    JOIN origen orig on   orig.id = ' . $idOrigen . '  
                                                    JOIN destino dest on  dest.id  = ' . $idDestino . ' 
                                                    JOIN Cabina cab on cab.id  = ' . $idCabina . '   
                                                    JOIN Servicio serv on serv.id = ' . $idServicio . '   
                                                    WHERE res.id =' . $idReserva);

        $datosCompletos = [];
        foreach ($query as $resultado) {

            $datosCompletos['nombre'] = $resultado['nombre'];
            $datosCompletos['apellido'] = $resultado['apellido'];
            $datosCompletos['dni'] = $resultado['dni'];
            $datosCompletos['origen'] = $resultado['Origen'];
            $datosCompletos['destino'] = $resultado['Destino'];
            $datosCompletos['tipo_viaje'] = $resultado['Tipo de viaje'];
            $datosCompletos['cabina'] = $resultado['Cabina'];
            $datosCompletos['servicio'] = $resultado['Servicio'];
            $datosCompletos['fecha_partida'] = $fechaPartida;
            $datosCompletos['total'] = $total;

        }

        return $datosCompletos;
    }

    public function buscarVueloPorId($idVuelo){
        $vuelo = $this->database->query("SELECT v.id AS id_vuelo, v.fecha_partida, v.id_lugar_origen as id_origen, l.nombre AS origen, m.capacidad, m.cabina_t AS cabina_turista, m.cabina_e AS cabina_ejecutivo, m.cabina_p AS cabina_primera, v.duracion_total AS total_horas, tv.id AS id_tipo_viaje, tv.descripcion AS tipo_viaje,  e.matricula, m.nombre AS modelo, m.tipo_equipo, te.nombre AS categoria_equipo
                                        FROM viaje v
                                        JOIN lugar l ON l.id=v.id_lugar_origen
                                        JOIN tipo_viaje tv ON tv.id=v.id_tipo_viaje
                                        JOIN equipo e ON e.id=v.id_equipo
                                        JOIN modelo m ON m.id=e.tipo_modelo
                                        JOIN tipo_equipo te ON te.id=m.tipo_equipo
                                        WHERE v.id='$idVuelo'");

        $lugaresDisponiblesPorCabina = $this->buscarLugaresDisponiblesPorId($idVuelo);

        $disponibleEjecutivo = false;
        $disponiblePrimera = false;
        $disponibleTurista = false;

        if($lugaresDisponiblesPorCabina["disponible_ejecutivo"]<=0){
            $disponibleEjecutivo = 'Agotado';
        }
        if($lugaresDisponiblesPorCabina["disponible_primera"]<=0){
            $disponiblePrimera = 'Agotado';
        }
        if($lugaresDisponiblesPorCabina["disponible_turista"]<=0){
            $disponibleTurista = 'Agotado';
        }

        $vuelo [0]["disponible_ejecutivo"] = $disponibleEjecutivo;
        $vuelo [0]["disponible_primera"] = $disponiblePrimera;
        $vuelo [0]["disponible_turista"] = $disponibleTurista;

        return $vuelo;
    }

    public function buscarCabinasPorIdVuelo($idVuelo){
        $cabinas = $this->database->query("SELECT * from cabina");

        $datosVuelo = $this->buscarVueloPorId($idVuelo);


        $result = array();

        foreach ($cabinas as $cabina){
            if($datosVuelo[0]["cabina_turista"]>0){
                if($cabina["descripcion"] == "Turista")
                    $result[]= ["id"=>$cabina["id"], "descripcion"=>$cabina["descripcion"], "precio"=>$cabina["precio"], "disponible"=>$datosVuelo[0]["disponible_turista"]];
            }
            if($datosVuelo[0]["cabina_ejecutivo"]>0){
                if($cabina["descripcion"] == "Ejecutivo")
                    $result[]= ["id"=>$cabina["id"], "descripcion"=>$cabina["descripcion"], "precio"=>$cabina["precio"], "disponible"=>$datosVuelo[0]["disponible_ejecutivo"]];
            }
            if($datosVuelo[0]["cabina_primera"]>0){
                if($cabina["descripcion"] == "Primera")
                    $result[]= ["id"=>$cabina["id"], "descripcion"=>$cabina["descripcion"], "precio"=>$cabina["precio"],"disponible"=>$datosVuelo[0]["disponible_primera"]];
            }
        }
        return $result;
    }

    public function buscarLugaresDisponiblesPorId($idVuelo){
        $capacidadPorCabina = $this->database->query("SELECT m.cabina_t AS Turista, m.cabina_e AS Ejecutivo, m.cabina_p AS Primera
                                                    FROM viaje v
                                                    JOIN equipo e ON e.id=v.id_equipo
                                                    JOIN modelo m ON m.id=e.tipo_modelo
                                                    WHERE v.id='$idVuelo'");


        $reservasActualesPorCabina = $this->database->query("SELECT c.id, c.descripcion
                                                    FROM reserva r
                                                    JOIN cabina c ON c.id=r.id_cabina
                                                    WHERE id_viaje = '$idVuelo'");

        $ocupadasPrimera = 0;
        $ocupadasEjecutivo = 0;
        $ocupadasTurista = 0;

        for($i=0; $i<sizeof($reservasActualesPorCabina); $i++){
            if($reservasActualesPorCabina[$i]["descripcion"] == 'Primera'){
                $ocupadasPrimera++;
            }
            if($reservasActualesPorCabina[$i]["descripcion"] == 'Ejecutivo'){
                $ocupadasEjecutivo++;
            }
            if($reservasActualesPorCabina[$i]["descripcion"] == 'Turista'){
                $ocupadasTurista++;
            }
        }

        $disponiblesPrimera = $capacidadPorCabina[0]["Primera"] - $ocupadasPrimera;
        $disponiblesTurista = $capacidadPorCabina[0]["Turista"] - $ocupadasTurista;
        $disponiblesEjecutivo = $capacidadPorCabina[0]["Ejecutivo"] - $ocupadasEjecutivo;

        $disponiblesPorCabina = ["disponible_primera"=>$disponiblesPrimera, "disponible_turista"=>$disponiblesTurista, "disponible_ejecutivo"=>$disponiblesEjecutivo];

        return $disponiblesPorCabina;
    }


    public function calcularPrecioViajePorIdVuelo($idVuelo){
        $precioViaje = null;

        $query = $this->database->query("SELECT tv.id
                                        FROM viaje v
                                        JOIN lugar l ON l.id=v.id_lugar_origen
                                        JOIN tipo_viaje tv ON tv.id=v.id_tipo_viaje
                                        WHERE v.id='$idVuelo'");

        if($query[0]["id"]==3){
            $precioViaje = 2800;
        }
        else{
            $precioViaje = 1900;
        }

        return $precioViaje;
    }

    public function precioCabinaPorId($id_cabina){
        return $this->database->query("SELECT precio FROM cabina WHERE id= '$id_cabina'");
    }

    public function precioServicioPorId($id_servicio){
        return $this->database->query("SELECT precio FROM servicio WHERE id= '$id_servicio'");
    }

    public function determinarEstadoReservaSegunFecha($fecha_partida){
        $estadoReserva = 1;

        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $hoy = date('Y-m-d H:i');

        $firstDate  =$hoy;
        $secondDate = $fecha_partida;
        $dateDifference = abs(strtotime($secondDate) - strtotime($firstDate));

        $unDíaEnSegundos = 86400;

        if($dateDifference<$unDíaEnSegundos){
            $estadoReserva = 2;
        }

        return $estadoReserva;
    }

}