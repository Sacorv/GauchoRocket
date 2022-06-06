<?php

class BusquedaModel{

    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function buscarDestinos($origen, $destino){
        return $this->database->query("SELECT *
                                        FROM viaje v
                                        JOIN tipo_viaje tv ON tv.id=v.id_tipo_viaje JOIN duracion d ON d.id_tipo_viaje=tv.id JOIN lugar l ON l.id=d.id_lugar
                                        WHERE v.id_lugar_origen = '$origen' AND l.id='$destino' GROUP BY v.id;");

    }

}