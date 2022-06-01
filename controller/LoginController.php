<?php

class LoginController {
    private $loginModel;

    public function __construct($loginModel) {
        $this->loginModel = $loginModel;
    }

    public function execute() {

        $usuario = $_POST["usuario"];
        $password = $_POST["password"];
        $result = $this->loginModel->login($usuario, $password);
        $nombreUsuario = $this->loginModel->obtenerNombreDelUsuario($usuario);



        if($result){
            $_SESSION["user"] = $nombreUsuario;
            $_SESSION["login"] = false;
            $_SESSION["bienvenida"] = true;
            header('location:/destinos/executeLogin');

        }else{
            $_SESSION["login"] = true;
            $_SESSION["bienvenida"] = false;
            session_destroy();
            header('location:/destinos/executeLoginFail');

        }
        var_dump($result);
        exit();
    }

    private function validateParamExists($param){
        return $_POST[$param] ?? "" ;
    }



}