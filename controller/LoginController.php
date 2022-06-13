<?php

class LoginController {
    private $loginModel;
    private $printer;

    public function __construct( $loginModel , $printer) {
        $this->loginModel = $loginModel;
        $this->printer = $printer;
    }

    public function execute() {
        $this->printer->generateView('loginView.html');
    }

    public function executeLogin(){
        $data['usuario'] = $_SESSION["user"];
        $data['bienvenida'] = $_SESSION["bienvenida"];
        $this->printer->generateView('inicioView.html' , $data);
    }
    public function executeLoginFail(){
        $data['loginFail'] = "loginFail";

        $this->printer->generateView('loginView.html' , $data);
    }

    public function ejecutamosLogin() {

        $usuario = $_POST["usuario"];
        $password = $_POST["password"];
        $result = $this->loginModel->login($usuario, $password);
        $nombreUsuario = $this->loginModel->obtenerNombreDelUsuario($usuario);



        if($result){
            $_SESSION["user"] = $nombreUsuario;
            $_SESSION["login"] = false;
            $_SESSION["bienvenida"] = true;
            header('location:/login/executeLogin');

        }else{
            $_SESSION["login"] = true;
            $_SESSION["bienvenida"] = false;
            session_destroy();
            header('location:/login/executeLoginFail');

        }
        var_dump($result);
        exit();
    }

    private function validateParamExists($param){
        return $_POST[$param] ?? "" ;
    }



}