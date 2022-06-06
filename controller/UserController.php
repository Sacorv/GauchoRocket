<?php

class UserController
{
    private $userModel;
    private $printer;

    public function __construct($userModel, $printer)
    {
        $this->userModel = $userModel;
        $this->printer = $printer;
    }

    public function getUsers() {
        $users = $this->userModel->allUsers();
        $data = ["usuarios" => $users];
        $this->printer->generateView('userView.html', $data);
    }

    public function execute() {
        $this->printer->generateView('registerView.html');
    }


    public function saveUser() {
        $firstName = $_POST["nombre"];
        $lastName = $_POST["apellido"];
        $dni = $_POST["dni"];
        $email = $_POST["email"];
        $pass = $_POST["pass"];
        $repeatPass = $_POST["repeat-pass"];

        $resultCreate = $this->userModel->createUser($firstName, $lastName, $dni, $email, $pass, $repeatPass);
        $this->printer->generateView($resultCreate);
    }

}