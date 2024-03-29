<?php
class LoginController
{
    private $loginModel;
    private $printer;

    public function __construct($loginModel, $printer)
    {

        $this->loginModel = $loginModel;
        $this->printer = $printer;

    }

    public function execute()
    {
        $data = array();

        if (isset($_SESSION["error"]) && $_SESSION["error"]) {
            $data["campoIncorrecto"] = "Email y/o contraseña incorrectos.";
            $data["campoIncompleto"] = "Por favor, complete todos los campos";
            $data["errorValidacion"] = "Por favor, valide su cuenta via mail.";
            session_unset();
            session_destroy();
        }
        $this->printer->generateView("/loginView.html", $data);
    }

    public function validarLogin()
    {

        $email = $_POST["usuario"] ?? $_SESSION["error"] = true;
        $password = $_POST["password"] ?? $_SESSION["error"] = true;

        $usuarioValido = $this->loginModel->getUsuarioPorEmailYPassword($email, $password);


        if ($usuarioValido!="") {


            $_SESSION["logueado"] = 1;
            $_SESSION["user"] = $usuarioValido[0];
            $_SESSION["nombre"] = $usuarioValido[0]["nombre"];
            $_SESSION["tipo"] = $usuarioValido[0]["tipo"];
            $_SESSION["verificado"] = $usuarioValido[0]["Verificado"];
            $_SESSION["id"] = $usuarioValido[0]["id"];
            $_SESSION["codigo_viajero"] = $usuarioValido[0]["codigo_viajero"];

            if (isset($_SESSION["tipo"]) && $_SESSION["tipo"] == 1) {
                $_SESSION["esAdmin"] = true;

                header("location: /");
                exit();
            }
            else{
                if (isset($_SESSION["tipo"]) && $_SESSION["tipo"] == 2) {
                    $_SESSION["esCliente"] = true;

                    header("location: /");
                    exit();


                } else {
                    $_SESSION["error"] = true;

                    header("location: /login");
                    exit();
                }

            }

        }

    }