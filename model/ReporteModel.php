<?php
class ReporteModel{

    private $database;

    public function __construct($database)
    {

        $this->database = $database;
    }

    public function getCabinaMasVendida()
    {
        $resultado=$this->database->query("SELECT id_cabina,COUNT(id_cabina) as 'Cantidad' FROM reserva GROUP BY id_cabina ORDER BY cantidad DESC");
        $cabinaMasVendida=$resultado[0];
        echo var_dump($cabinaMasVendida);
        return $cabinaMasVendida;


    }



   
}