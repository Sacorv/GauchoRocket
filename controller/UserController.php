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

    //HACER: QUE LOS DATOS SEAN TOMADOS DE LO QUE SE ENVÍE EN EL FORM Y SACAR LO HARCODEADO EN LA QUERY DE CREATEuSER EN USERMODEL
    public function saveUser() {
        $firstName = $_POST["nombre"];
        $lastName = $_POST["apellido"];
        $dni = $_POST["dni"];
        $email = $_POST["email"];
        $pass = $_POST["pass"];
        $repeatPass = $_POST["repeat-pass"];

        echo $firstName . $lastName . $dni . $email . $pass. $repeatPass;

        $resultCreate = $this->userModel->createUser($firstName, $lastName, $dni, $email, $pass, $repeatPass);
        $this->printer->generateView($resultCreate);
    }

}