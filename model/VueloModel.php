<?php
class VueloModel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function getDestinos(){
        return $this->database->query('SELECT * FROM lugar WHERE habilitado_destino = 1 AND lugar.nombre NOT IN ("buenos aires", "ankara", "neptuno")');
    }
    public function getOrigenes(){
        return $this->database->query('SELECT * FROM lugar WHERE habilitado_origen = 1 AND lugar.nombre NOT IN ("titan", "neptuno")');
    }
}