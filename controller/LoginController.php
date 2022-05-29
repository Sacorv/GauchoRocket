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

        if($result){
            $_SESSION["user"] = $usuario;
            $_SESSION["login"] = false;
            $_SESSION["bienvenida"] = true;
            header('location:/');

        }else{
            session_destroy();
            header('location:/');
            echo "<div> <h1>Usuario y/o contraseña invalidos </h1> </div>";
        }
    }



}