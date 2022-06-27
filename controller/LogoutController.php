<?php

class LogoutController
{

    public function execute(){
            session_unset();
            session_destroy();
            header("location: /");
            exit();

    }
}