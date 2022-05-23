<?php

class UserController {
    private $userModel;
    private $printer;

    public function __constructor($userModel, $printer) {
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

}