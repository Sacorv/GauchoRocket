<?php

class BusquedaModel{

    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function buscarCodigoViajero($idUsuario){
        return $this->database->query("SELECT codigo_viajero FROM usuario WHERE id='$idUsuario'");
    }

    public function buscarDestinos($idDeOrigen, $idDestino){

        $resultQuery = null;

        if($idDeOrigen==1 or $idDeOrigen==2){
            $resultQuery = $this->database->query("SELECT v.id, v.fecha_partida, v.id_lugar_origen as id_origen, l2.nombre AS origen, d.id_lugar AS id_destino, l.nombre AS destino, v.duracion_total AS total_horas, tv.descripcion AS tipo_viaje, e.matricula, m.nombre AS modelo, m.tipo_equipo, te.nombre AS categoria_equipo
                                                FROM viaje v
                                                JOIN tipo_viaje tv ON tv.id=v.id_tipo_viaje
                                                JOIN duracion d ON d.id_tipo_viaje=tv.id
                                                JOIN equipo e ON e.id=v.id_equipo
                                                JOIN modelo m ON m.id=e.tipo_modelo
                                                JOIN tipo_equipo te ON te.id=m.tipo_equipo
                                                JOIN lugar l ON l.id=d.id_lugar
                                                JOIN lugar l2 ON l2.id=v.id_lugar_origen
                                                WHERE d.id_lugar='$idDestino' AND v.id_lugar_origen='$idDeOrigen' AND d.id_tipo_equipo=te.id
                                                ORDER BY v.fecha_partida");
        }
        else{
            $tipo_viaje = $this->database->query("SELECT id_tipo_viaje FROM duracion WHERE id_lugar = '$idDeOrigen'
                                                  GROUP BY id_tipo_viaje");

            if(sizeof($tipo_viaje)==1){
                $idTipoViaje = $tipo_viaje[0]["id_tipo_viaje"];

                $resultQuery = $this->database->query("SELECT v.id, v.fecha_partida, v.id_lugar_origen as id_origen, l2.nombre AS origen, d.id_lugar AS id_destino, l.nombre AS destino, v.duracion_total AS total_horas, tv.descripcion AS tipo_viaje, e.matricula, m.nombre AS modelo, m.tipo_equipo, te.nombre AS categoria_equipo
                                                        FROM viaje v
                                                        JOIN tipo_viaje tv ON tv.id=v.id_tipo_viaje
                                                        JOIN duracion d ON d.id_tipo_viaje=tv.id
                                                        JOIN equipo e ON e.id=v.id_equipo
                                                        JOIN modelo m ON m.id=e.tipo_modelo
                                                        JOIN tipo_equipo te ON te.id=m.tipo_equipo
                                                        JOIN lugar l ON l.id=d.id_lugar
                                                        JOIN lugar l2 ON l2.id=v.id_lugar_origen
                                                        WHERE d.id_lugar='$idDestino' AND v.id_tipo_viaje='$idTipoViaje' AND d.id_tipo_equipo=te.id
                                                        ORDER BY v.fecha_partida");
            }
            else{
                $resultQuery = $this->database->query("SELECT v.id, v.fecha_partida, v.id_lugar_origen as id_origen, l2.nombre AS origen, d.id_lugar AS id_destino, l.nombre AS destino, v.duracion_total AS total_horas, tv.descripcion AS tipo_viaje, e.matricula, m.nombre AS modelo, m.tipo_equipo, te.nombre AS categoria_equipo
                                                        FROM viaje v
                                                        JOIN tipo_viaje tv ON tv.id=v.id_tipo_viaje
                                                        JOIN duracion d ON d.id_tipo_viaje=tv.id
                                                        JOIN equipo e ON e.id=v.id_equipo
                                                        JOIN modelo m ON m.id=e.tipo_modelo
                                                        JOIN tipo_equipo te ON te.id=m.tipo_equipo
                                                        JOIN lugar l ON l.id=d.id_lugar
                                                        JOIN lugar l2 ON l2.id=v.id_lugar_origen
                                                        WHERE d.id_lugar='$idDestino' AND d.id_tipo_equipo=te.id
                                                        ORDER BY v.fecha_partida");
            }
        }

        $arrayVuelosCompletos = [];

        foreach ($resultQuery as $result){
            $idDestino = $result["id_destino"];
            $idOrigen = $result["id_origen"];
            $idVuelo = $result["id"];

            //Query para cada id de vuelo
            $query = $this->database->query("SELECT v.id, v.fecha_partida, l2.nombre AS origen, d.id_lugar AS id_destino, l.nombre AS escala, d.duracion_hs AS horas, d.orden, v.duracion_total AS total_horas, tv.descripcion AS tipo_viaje, e.matricula, m.nombre AS modelo, m.tipo_equipo, te.nombre AS categoria_equipo 
                                            FROM viaje v
                                            JOIN tipo_viaje tv ON tv.id=v.id_tipo_viaje
                                            JOIN duracion d ON d.id_tipo_viaje=tv.id
                                            JOIN equipo e ON e.id=v.id_equipo
                                            JOIN modelo m ON m.id=e.tipo_modelo
                                            JOIN tipo_equipo te ON te.id=m.tipo_equipo
                                            JOIN lugar l ON l.id=d.id_lugar
                                            JOIN lugar l2 ON l2.id=v.id_lugar_origen
                                            WHERE d.id_lugar<='$idDestino' AND v.id_lugar_origen='$idOrigen' AND d.id_tipo_equipo=te.id AND v.id='$idVuelo'
                                            ORDER BY v.fecha_partida");

            $escalas = [];
            $escala = null;

            for ($i=0;$i<count($query);$i++){
                if ($i==0){
                    $hours= $query[$i]["horas"];
                    $date = $query[$i]["fecha_partida"];
                    $llegada = date('Y-m-d H:i', strtotime($date . ' + '.$hours. 'hours'));

                    $escala = ["id_destino"=>$query[$i]["id_destino"],"destino" => $query[$i]["escala"], "fecha_llegada" => $llegada];
                    $escalas[] = $escala;
                }
                else{
                    $hours= $query[$i]["horas"];
                    $date = $escalas[($i-1)]["fecha_llegada"];
                    $llegada = date('Y-m-d H:i', strtotime($date . ' + '.$hours. 'hours'));

                    $escala = ["id_destino"=>$query[$i]["id_destino"],"destino" => $query[$i]["escala"], "fecha_llegada" => $llegada];
                    $escalas[] = $escala;
                }

            }

            $disponible = false;

            if($idDeOrigen==1 or $idDeOrigen==2){

                // Calcular disponibilidad del viaje
                $lugaresDisponibles = $this->buscarLugaresDisponiblesPorOrigenyDestino($idOrigen, $idDestino, $idVuelo);

                if($lugaresDisponibles>0){
                    $disponible = true;
                }

                $fechaValida = $this->esValidaFechaPartida($result["fecha_partida"]);

                $vuelo = ["disponible"=>$disponible,"id_vuelo" => $idVuelo, "fecha_partida" => $result["fecha_partida"], "fecha_invalida"=>$fechaValida, "id_origen" => $idOrigen, "origen" => $result["origen"], "escala" => $escalas,"id_destino" => $idDestino, "destino" => $result["destino"], "categoria_viaje" => $result["tipo_viaje"], "modelo_equipo" => $result["modelo"], "matricula_equipo" =>$result["matricula"], "categoria_equipo" => $result["categoria_equipo"]];

                $arrayVuelosCompletos[] = $vuelo;
            }
            else{
                $escalaDiferenteOrigen = [];
                $fechaSalida  = null;
                $origen = null;

                for($i=0; $i<count($escalas); $i++){
                    if ($escalas[$i]["id_destino"]==$idDeOrigen){
                        $fechaSalida = $escalas[$i]["fecha_llegada"];
                        $origen = $escalas[$i]["destino"];
                    }
                    else{
                        if($escalas[$i]["id_destino"]>$idDeOrigen){
                            $escalaDiferenteOrigen[] = $escalas[$i];
                        }
                    }
                }

                // Calcular disponibilidad del viaje
                $lugaresDisponibles = $this->buscarLugaresDisponiblesPorOrigenyDestino($idDeOrigen, $idDestino, $idVuelo);

                if($lugaresDisponibles>0){
                    $disponible = true;
                }

                $fechaValida = $this->esValidaFechaPartida($fechaSalida);

                $vuelo = ["disponible"=>$disponible,"id_vuelo" => $idVuelo, "fecha_partida" => $fechaSalida, "fecha_invalida"=>$fechaValida, "id_origen" => $idDeOrigen, "origen" => $origen, "escala" => $escalaDiferenteOrigen,"id_destino" => $idDestino, "destino" => $result["destino"], "categoria_viaje" => $result["tipo_viaje"], "modelo_equipo" => $result["modelo"], "matricula_equipo" =>$result["matricula"], "categoria_equipo" => $result["categoria_equipo"]];

                $arrayVuelosCompletos[] = $vuelo;
            }
        }
        return $arrayVuelosCompletos;
    }

    public function buscarViajes($id){
        $viajes = $this->database->query("SELECT v.id AS id_vuelo, v.id_lugar_origen AS id_origen, v.fecha_partida, l.nombre AS origen, DATE_ADD(v.fecha_partida, INTERVAL v.duracion_total HOUR) AS fecha_llegada 
                                        FROM viaje v
                                        JOIN lugar l ON l.id=v.id_lugar_origen
                                        WHERE v.id_tipo_viaje='$id'
                                        ORDER BY v.fecha_partida");

        for($i=0; $i<sizeof($viajes); $i++){
            $lugaresDisponibles = $this->buscarLugaresDisponiblesPorId($viajes[$i]["id_vuelo"]);

            $disponible=false;
            if($lugaresDisponibles>0){
                $disponible = true;
            }
            $viajes[$i]["disponible"] = $disponible;

            $fechaValida = $this->esValidaFechaPartida($viajes[$i]["fecha_partida"]);

            $viajes[$i]["fecha_invalida"] = $fechaValida;

        }



        return $viajes;
    }

    public function buscarLugaresDisponiblesPorId($id){
        $capacidadTotal = $this->database->query("SELECT m.capacidad 
                                                    FROM viaje v
                                                    JOIN equipo e ON e.id=v.id_equipo
                                                    JOIN modelo m ON m.id=e.tipo_modelo
                                                    WHERE v.id='$id'");


        $reservasActuales = $this->database->query("SELECT COUNT(*) AS cantidad_reservas
                                                    FROM reserva
                                                    WHERE id_viaje = '$id'");

        $lugaresDisponibles = $capacidadTotal[0]["capacidad"] -$reservasActuales[0]["cantidad_reservas"];

        return $lugaresDisponibles;
    }



    public function buscarLugaresDisponiblesPorOrigenyDestino($idOrigen, $idDestino, $idVuelo){

        $capacidadTotal = $this->database->query("SELECT m.capacidad
                                                    FROM viaje v
                                                    JOIN tipo_viaje tv ON tv.id=v.id_tipo_viaje
                                                    JOIN duracion d ON d.id_tipo_viaje=tv.id
                                                    JOIN equipo e ON e.id=v.id_equipo
                                                    JOIN modelo m ON m.id=e.tipo_modelo
                                                    JOIN tipo_equipo te ON te.id=m.tipo_equipo
                                                    JOIN lugar l ON l.id=d.id_lugar
                                                    JOIN lugar l2 ON l2.id=v.id_lugar_origen
                                                    WHERE d.id_tipo_equipo=te.id AND v.id='$idVuelo'
                                                    GROUP BY m.capacidad");


        $reservasActuales = $this->database->query("SELECT id_origen, id_destino 
                                                    FROM reserva 
                                                    WHERE id_viaje = '$idVuelo'");

        $cantidadOcupadas = 0;
       for($i=0; $i<sizeof($reservasActuales); $i++){
           if($reservasActuales[$i]["id_destino"]>$idOrigen and $reservasActuales[$i]["id_origen"]<$idDestino){
               $cantidadOcupadas++;
           }
       }

       $lugaresDisponibles = $capacidadTotal[0]["capacidad"] - $cantidadOcupadas;

       return $lugaresDisponibles;
    }

    public function esValidaFechaPartida($fechaPartida){
        $esInvalida = false;

        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $hoy = date('Y-m-d H:i:s');

        if($fechaPartida<$hoy){
            $esInvalida = true;
        }
        return $esInvalida;
    }


}