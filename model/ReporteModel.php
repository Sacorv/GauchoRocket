<?php
class ReporteModel{

    private $database;

    public function __construct($database)
    {

        $this->database = $database;
    }

    public function getCabinaMasVendida()
    {
        $resultado=$this->database->query("SELECT id_cabina,COUNT(id_cabina)  as 'Cantidad', c.descripcion FROM reserva r INNER JOIN cabina c on r.id_cabina = c.id   GROUP BY id_cabina ORDER BY cantidad DESC");
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
    public function getTasaOcupacion(){
return $this->database->query("SELECT r.id_viaje, Count(r.id_viaje) as 'Asientos Ocupados',m.capacidad as 'Asientos Totales',
FORMAT((Count(r.id_viaje)/m.capacidad)*100,2) as 'Tasa'from reserva r inner join viaje v on r.id_viaje=v.id inner join equipo e 
on v.id_equipo=e.id inner join modelo m on e.tipo_modelo= m.id group by r.id_viaje");
    }



   
}