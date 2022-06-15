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
include_once('model/VueloModel.php');
include_once('controller/BusquedaController.php');
include_once('model/BusquedaModel.php');
require_once('helper/EmailHelper.php');
require_once('helper/PHPMailer/PHPMailer.php');

class Configuration
{


    public function getUserController()
    {
        return new UserController($this->getUserModel(), $this->getPrinter(), $this->getMailer());
    }


    public function getLoginController()
    {
        return new LoginController($this->getLoginModel(), $this->getPrinter());
    }


    public function getInicioController()
    {
        return new InicioController($this->getPrinter(), $this->getVueloModel());
    }


    public function getDestinosController()
    {
        return new DestinosController($this->getPrinter());
    }

    public function getBusquedaController()
    {
        return new BusquedaController($this->getBusquedaModel(), $this->getPrinter());
    }


    private function getUserModel()
    {
        return new UserModel($this->getDatabase() , $this->getValidatorHelper());
    }


    public function getVueloModel()
    {
        return new VueloModel($this->getDatabase());
    }


    private function getBusquedaModel()
    {
        return new BusquedaModel($this->getDatabase());
    }


    private function getDatabase()
    {
        return new MySqlDatabase('localhost', 'root', '40460303', 'gaucho_rocket');
    }


    public function getLoginModel()
    {
        return new LoginModel($this->getDatabase());
    }


    private function getPrinter()
    {
        return new MustachePrinter("view", $this->getLoginHelper());
    }


    public function getRouter()
    {
        return new Router($this, "getInicioController", "execute");
    }

    private function getMailer()
    {
        return new EmailHelper();
    }

    public function getTurnosController()
    {
        include_once('controller/TurnosController.php');
        return new TurnosController($this->getPrinter());
    }

    public function getLoginHelper()
    {
        include_once('helper/LoginHelper.php');
        return new LoginHelper();
    }

    public function getValidatorHelper()
    {
        include_once('helper/Validator.php');
        return new Validator();
    }
}

