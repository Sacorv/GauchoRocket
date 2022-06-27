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
        return $cabinaMasVendida;


    }
    public function getFacturacionMensual(){

        return $this->database->query("SELECT MONTH(fecha_partida) as 'Mes',SUM(subtotal_tramos+precio_cabina+precio_servicio) as 'Facturacion Total'
        from reserva
        group by MONTH(fecha_partida)
        order by MONTH(fecha_partida)"); 
       
    }

    public function getFacturaCliente(){
        return $this->database->query("SELECT id_usuario,SUM(subtotal_tramos+precio_cabina+precio_servicio) as 'Facturacion Cliente',nombre,apellido,dni
        FROM reserva r INNER JOIN usuario u on r.id_usuario=u.id
        GROUP BY id_usuario");

    }



   
}