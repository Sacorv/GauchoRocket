<?php

class LogoutController
{

    public function execute(){
        if(isset($_GET["logout"])){
            header("location: /");
            exit();
        }

    }
}