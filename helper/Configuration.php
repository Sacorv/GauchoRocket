<?php
include_once('helper/MySqlDatabase.php');
include_once('helper/Router.php');
require_once('helper/MustachePrinter.php');
include_once('controller/LoginController.php');
include_once('controller/DestinosController.php');
include_once('model/LoginModel.php');
require_once('third-party/mustache/src/Mustache/Autoloader.php');
include_once('controller/UserController.php');
include_once('model/UserModel.php');
include_once('controller/InicioController.php');
include_once('model/VueloModel.php');
include_once('controller/BusquedaController.php');
include_once('model/BusquedaModel.php');

class Configuration {


        public function getUserController() {
            return new UserController($this->getUserModel(), $this->getPrinter());
        }


        public function getLoginController() {
            return new LoginController($this->getLoginModel(), $this->getPrinter());
        }


    public function getInicioController() {
        return new InicioController($this->getPrinter(),$this->getVueloModel());
    }


    public function getDestinosController() {
        return new DestinosController($this->getPrinter());
    }

    public function getBusquedaController(){
        return new BusquedaController($this->getBusquedaModel(), $this->getPrinter());
    }


    private function getUserModel() {
        return new UserModel($this->getDatabase());
    }


    public function getVueloModel(){
        return new VueloModel($this->getDatabase());
    }


    private function getBusquedaModel(){
        return new BusquedaModel($this->getDatabase());
    }


    private function getDatabase() {
       return new MySqlDatabase('localhost','root','root','gaucho_rocket');
    }



    public function getLoginModel()
    {
        return new LoginModel($this->getDatabase());
    }


        private function getPrinter(){
            return new MustachePrinter("view", $this->getLoginHelper());
        }


    public function getRouter(){
        return new Router($this, "getInicioController", "execute");
    }

       public function getTurnosController(){
        include_once ('controller/TurnosController.php');
        return new TurnosController($this->getUserModel(),$this->getPrinter());
       }
       public function getLoginHelper(){
            include_once('helper/LoginHelper.php');
            return new LoginHelper();
       }

}

