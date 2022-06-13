<?php

class LoginHelper
{

    public function executeLogin(){
        if(isset($_GET["logout"])){
            session_unset();
            session_destroy();
            header("location: /");
            exit();
        }

        $data=array();

        if(isset($_SESSION["esCliente"]) && $_SESSION["esCliente"]){
            $data["esCliente"]=true;
        }
        if(isset($_SESSION["esAdmin"]) && $_SESSION["esAdmin"]){
            $data["esAdmin"] = true;
        }
       return $data;
    }
}