<?php
include_once('helper/MySqlDatabase.php');
include_once('helper/Router.php');
require_once('helper/MustachePrinter.php');
include_once('controller/LoginController.php');
include_once('controller/ToursController.php');
include_once('controller/DestinosController.php');
include_once('model/LoginModel.php');
include_once('model/TourModel.php');
require_once('third-party/mustache/src/Mustache/Autoloader.php');
include_once('controller/UserController.php');
include_once('model/UserModel.php');
include_once('model/ViajeModel.php');
include_once('controller/InicioController.php');
include_once('model/PaisModel.php');
include_once('controller/BusquedaController.php');
include_once('model/BusquedaModel.php');

class Configuration {


        public function getUserController() {
            return new UserController($this->getUserModel(), $this->getPrinter());
        }


        public function getLoginController() {
            return new LoginController($this->getLoginModel(), $this->getPrinter(), $this->getPaisModel());
        }


    public function getInicioController() {
        return new InicioController($this->getViajeModel(), $this->getPrinter(),$this->getPaisModel());
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


    public function getPaisModel(){
        return new PaisModel($this->getDatabase());
    }


    private function getBusquedaModel(){
        return new BusquedaModel($this->getDatabase());
    }


    private function getDatabase() {
       return new MySqlDatabase('localhost','root','','gaucho_rocket');
    }



        public function getLoginModel()
        {
            return new LoginModel($this->getDatabase());
        }

        private function getViajeModel()
        {
            return new ViajeModel($this->getDatabase());
        }



        private function getPrinter(){
            return new MustachePrinter("view");
        }

        public function getRouter(){
            return new Router($this, "getInicioController", "execute");
        }



}

