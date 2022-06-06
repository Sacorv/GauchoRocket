<?php
class PaisModel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function getDestinos(){
        return $this->database->query('SELECT * FROM lugar WHERE habilitado_destino = 1');
    }
    public function getOrigenes(){
        return $this->database->query('SELECT * FROM lugar WHERE habilitado_origen = 1');
    }
}