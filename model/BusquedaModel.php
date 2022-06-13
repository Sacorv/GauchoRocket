<?php

class BusquedaModel{

    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function buscarDestinos($idOrigen, $idDestino){

        $resultQuery = $this->database->query("SELECT v.id, v.fecha_partida, v.id_lugar_origen as id_origen, l2.nombre AS origen, d.id_lugar AS id_destino, l.nombre AS destino, v.duracion_total AS total_horas, tv.descripcion AS tipo_viaje, e.matricula, m.nombre AS modelo, m.tipo_equipo, te.nombre AS categoria_equipo
                                                FROM viaje v
                                                JOIN tipo_viaje tv ON tv.id=v.id_tipo_viaje
                                                JOIN duracion d ON d.id_tipo_viaje=tv.id
                                                JOIN equipo e ON e.id=v.id_equipo
                                                JOIN modelo m ON m.id=e.tipo_modelo
                                                JOIN tipo_equipo te ON te.id=m.tipo_equipo
                                                JOIN lugar l ON l.id=d.id_lugar
                                                JOIN lugar l2 ON l2.id=v.id_lugar_origen
                                                WHERE d.id_lugar='$idDestino' AND v.id_lugar_origen='$idOrigen' AND d.id_tipo_equipo=te.id");

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
                                            ORDER BY d.orden");

            $escalas = [];
            $escala = null;

            for ($i=0;$i<count($query);$i++){
                if ($i==0){
                    $hours= $query[$i]["horas"];
                    $date = $query[$i]["fecha_partida"];
                    $llegada = date('Y-m-d H:i:s', strtotime($date . ' + '.$hours. 'hours'));

                    $escala = ["destino" => $query[$i]["escala"], "fecha_llegada" => $llegada];
                    $escalas[] = $escala;
                }
                else{
                    $hours= $query[$i]["horas"];
                    $date = $escalas[($i-1)]["fecha_llegada"];
                    $llegada = date('Y-m-d H:i:s', strtotime($date . ' + '.$hours. 'hours'));

                    $escala = ["destino" => $query[$i]["escala"], "fecha_llegada" => $llegada];
                    $escalas[] = $escala;
                }

            }

            $vuelo = ["id_vuelo" => $idVuelo, "fecha_partida" => $result["fecha_partida"], "id_origen" => $idOrigen, "origen" => $result["origen"], "escala" => $escalas,"id_destino" => $idDestino, "destino" => $result["destino"], "categoria_viaje" => $result["tipo_viaje"], "modelo_equipo" => $result["modelo"], "matricula_equipo" =>$result["matricula"], "categoria_equipo" => $result["categoria_equipo"]];

            $arrayVuelosCompletos[] = $vuelo;
        }

        return $arrayVuelosCompletos;
        }

}