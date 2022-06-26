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
        $this->userModel->verificarUser($id);

    }

    public function saveUser() {
        $firstName = $_POST["nombre"];
        $lastName = $_POST["apellido"];
        $dni = $_POST["dni"];
        $email = $_POST["email"];
        $pass = $_POST["pass"];
        $repeatPass = $_POST["repeat-pass"];
        $id = random_int(0 , 999);

        $data = ['email' => $email];

        $_SESSION["dni"] = $dni;

         $this->mailer->enviarMail($email , $id);

        $resultCreate = $this->userModel->createUser($firstName, $lastName, $dni, $email, $pass, $repeatPass, $id);
        $this->printer->generateView($resultCreate , $data);
    }

}