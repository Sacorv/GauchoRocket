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
        }else{
            echo "<div><h3>Las contraseñas ingresadas no coinciden</h3></div>";
            return "registerView.html";
        }
        if($this->existsUser($email)){
            echo "<div><h3>El email ya se encuentra registrado</h3></div>";
            return "registerView.html";
        }else {
            $resultCreate = $this->database->create($firstName, $lastName, $dni, $email, $password);
            if ($resultCreate) {
                return "registerSuccessView.html";
            }
            return "registerView.html";
        }
    }

    public function existsUser ($email) {

       if( $this->database->validarMail($email) == 1){
           return true;
       }else{
           return false;
       }

    }

    public function updateCodigoViajero($codigo){
        $id=$_SESSION["id"];
        if(isset($id)){
            return $this->database->updateCodigoViajero($id,$codigo);

        }else{

            return "Error al actualizar el codigo de Viajero";
        }
    


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