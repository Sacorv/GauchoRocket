<?php

class UserController
{
    private $userModel;
    private $printer;
    private $mailer;

    public function __construct($userModel, $printer, $mailer)
    {
        $this->userModel = $userModel;
        $this->printer = $printer;
        $this->mailer = $mailer;
    }

    public function getUsers() {
        $users = $this->userModel->allUsers();
        $data = ["usuarios" => $users];
        $this->printer->generateView('userView.html', $data);
    }

    public function execute() {
        $this->printer->generateView('registerView.html');
    }

    public function register() {
        $ID = $_GET['id'];
        $data = ['id' => $ID];
        $this->printer->generateView('registerConfirmation.html' , $data);
    }

    public function confirmarCuenta(){
        $id = $_GET['id'];
        $data= [];
        if($this->userModel->verificarUser($id)){
            $data = ['seVerificoLaCuenta' => true];
            return $this->printer->generateView('registerConfirmValidation.html' , $data);
        }else{
            $data = ['seVerificoLaCuenta' => false];
            return $this->printer->generateView('registerConfirmValidation.html' , $data);
        }

    }

    public function saveUser() {
        $firstName = $_POST["nombre"];
        $lastName = $_POST["apellido"];
        $dni = $_POST["dni"];
        $email = $_POST["email"];
        $pass = $_POST["pass"];
        $repeatPass = $_POST["repeat-pass"];
        $idVerificacion = random_int(0 , 999);

        $data = ['email' => $email];
        $data = ['id' => $idVerificacion];

        $resultCreate = $this->userModel->createUser($firstName, $lastName, $dni, $email, $pass, $repeatPass, $idVerificacion);

        if($resultCreate == []){
            if($this->mailer->enviarMail($email , $idVerificacion)){
                return $this->printer->generateView('registerSuccesView.html');
            }
        }else{
            return $this->printer->generateView('registerView.html' , $resultCreate);
        }

    }

}