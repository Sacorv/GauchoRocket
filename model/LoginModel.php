<?php

class LoginModel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function login($mail, $password){
        $result= $this->database->login($mail, $password);
            if($result == 0){
               return false;
            }else{
                return true;
        }
    }
    public function obtenerNombreDelUsuario ($email){
        $result= $this->database->query("select nombre from usuario where email='$email'");
        return $result[0]["nombre"];

    }
}