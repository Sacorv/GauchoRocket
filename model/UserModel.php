<?php

class UserModel {

    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function allUsers () {
        return $this->database->query('SELECT * FROM usuario');
    }

    public function createUser ($firstName, $lastName, $dni, $email, $pass, $repeatPass) {
        //Validacion de la password
        if($this->isValidPass($pass, $repeatPass)){
            $password = md5($pass);

            echo $password;
            $resultCreate = $this->database->create($firstName, $lastName, $dni, $email, $password);

            if($resultCreate){
                return "registerSuccessView.html";
            }else{
                echo "<div><h3>El email ya se encuentra registrado</h3></div>";
                echo $resultCreate;
                return "registerView.html";
            }
        }else{
            echo "<div><h3>Las contraseñas ingresadas no coinciden</h3></div>";
            return "registerView.html";
        }

    }

    public function existsUser () {

    }

    public function editUser () {

    }

    public function deleteUser () {

    }

    private function isValidPass($pass, $repeatPass)
    {
        if($pass == $repeatPass){
            return true;
        }
        else{
            return false;
        }
    }


}