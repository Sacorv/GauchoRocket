<?php

class DestinosController {
    private $printer;

    public function __construct($printer) {
        $this->printer = $printer;
    }

    public function execute() {
        $this->printer->generateView('destinosView.html');
    }

    public function executeLogin(){
        $data['usuario'] = $_SESSION["user"];
        $data['bienvenida'] = $_SESSION["bienvenida"];
        $this->printer->generateView('destinosView.html' , $data);
    }
    public function executeLoginFail(){
        $data['loginFail'] = "loginFail";

        $this->printer->generateView('destinosView.html' , $data);
    }
}