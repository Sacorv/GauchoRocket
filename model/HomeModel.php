<?php

class HomeModel
{
    private MySqlDatabase $database;

    public function __construct($database){

        $this->database = $database;
    }
    public function getOrigins(){
        $query = "SELECT * FROM origen";
        return  $this->database->query($query);
    }

    public function getTipoViaje()
    {
        $query = "SELECT * FROM tipoViaje";
        return  $this->database->query($query);
    }

    public function getDestinys()
    {
        $query = "SELECT * FROM destino";
        return  $this->database->query($query);
    }
}