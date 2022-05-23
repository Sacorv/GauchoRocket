<?php

class ToursController {
    private $printer;
    private $tourModel;

    public function __construct($tourModel, $printer) {
        $this->tourModel = $tourModel;
        $this->printer = $printer;
    }

    public function execute() {
        $tours = $this->tourModel->getTours();
        $data = ["presentaciones" => $tours];
        $this->printer->generateView('toursView.html', $data);
    }
}