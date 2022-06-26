<?php
/*
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



}*/

class LoginController
{
    private  $loginModel;
    private  $printer;

    public function __construct($loginModel, $printer){

        $this->loginModel = $loginModel;
        $this->printer = $printer;

    }
    public function execute()
    {
        $data=array();

        if(isset($_SESSION["error"]) && $_SESSION["error"]){
            $data["campoIncorrecto"] = "Email y/o contraseña incorrectos.";
            $data["campoIncompleto"] = "Por favor, complete todos los campos";
            $data["errorValidacion"] = "Por favor, valide su cuenta via mail.";
            session_unset();
            session_destroy();
        }
        $this->printer->generateView("/loginView.html", $data);
    }

    public function validarLogin(){

            $email= $_POST["usuario"] ?? $_SESSION["error"]=true;
            $password=$_POST["password"] ?? $_SESSION["error"]=true;

            $usuarioValido = $this->loginModel->getUsuarioPorEmailYPassword($email, $password);

            if(!empty($usuarioValido)) {

                $_SESSION["logueado"] = 1;
                $_SESSION["user"] = $usuarioValido[0];
                $_SESSION["nombre"] = $usuarioValido[0]["nombre"];
                $_SESSION["tipo"] = $usuarioValido[0]["tipo"];
                $_SESSION["verificado"] = $usuarioValido[0]["verificado"];

                if (isset($_SESSION["tipo"]) && $_SESSION["tipo"] == 1) {
                    $_SESSION["esAdmin"] = true;
                }
                if (isset($_SESSION["tipo"]) && $_SESSION["tipo"] == 2) {
                    if($_SESSION["verificado"]==1){
                        $_SESSION["esCliente"] = true;
                    }else{
                        $_SESSION["error"]=true;
                    }
                }

                header("location: /");
                exit();

            }
            else{
                $_SESSION["error"]=true;
                header("location: /login");
                exit();
            }
        }

}