<?php

class DestinosController {

    private MustachePrinter $printer;
    private HomeModel $homeModel;

    public function __construct($homeModel, $printer) {
        $this->printer = $printer;
        $this->homeModel=$homeModel;
    }

    public function execute() {

        $origen= $this->homeModel->getOrigins();
        $tipoViaje= $this->homeModel->getTipoViaje();
        $destino= $this->homeModel->getDestinys();

        $data=["origen"=> $origen,  "destino"=>$destino, "tipoViaje"=> $tipoViaje];
        $this->printer->generateView('destinosView.html',$data);
    }
}