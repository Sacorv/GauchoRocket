<?php

class UserModel {

    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function allUsers () {
        return $this->database->query('SELECT * FROM usuario');
    }

    public function createUser () {

    }

    public function existsUser () {

    }

    public function editUser () {

    }

    public function deleteUser () {

    }


}