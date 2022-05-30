<?php
class PaisModel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function getDestinos(){
        return $this->database->query('SELECT * FROM destino');
    }
    public function getOrigenes(){
        return $this->database->query('SELECT * FROM origen');


    }
}