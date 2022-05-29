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
}