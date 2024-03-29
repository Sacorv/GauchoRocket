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
include_once('controller/ReportesController.php');
include_once('model/BusquedaModel.php');
include_once('model/ReporteModel.php');
include_once('controller/ReservaController.php');
include_once('model/ReservaModel.php');
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



    public function getInicioController() {

        return new InicioController($this->getPrinter(),$this->getVueloModel());

    }
    public function getReportesController(){

        return new ReportesController($this->getPrinter(),$this->getReporteModel(),$this->getPDFHelper());

       }

    public function getDestinosController() {

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
   
    private function getReporteModel()
    {
        return new ReporteModel($this->getDatabase());
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

        return new MySqlDatabase('localhost', 'root', '2203yba', 'gaucho_rocket');
}




    public function getLoginModel()
    {
        return new LoginModel($this->getDatabase());
    }

        private function getPrinter(){
            return new MustachePrinter("view");
        }



    public function getRouter()
    {
        return new Router($this, "getInicioController", "execute");
    }


    private function getMailer()
    {
        return new EmailHelper();
    }


       public function getLogoutController(){
            include_once('controller/LogoutController.php');
            return new LogoutController();
     }
       public function getTurnosController(){
        include_once ('controller/TurnosController.php');
        return new TurnosController($this->getUserModel(),$this->getPrinter());
       }

    public function getReservaController(){
        return new ReservaController($this->getReservaModel(),
                                        $this->getPrinter(),
                                        $this->getBusquedaModel(),
                                            $this->getQRHelper(),
                                            $this->getPDFHelper());
    }

    public function getReservaModel()
    {
        return new ReservaModel ($this->getDatabase());
    }


    public function getValidatorHelper()
    {
        include_once('helper/Validator.php');
        return new Validator();

    }

    public function getQRHelper()
    {
        include_once('helper/QRHelper.php');
        return new QRHelper();

    }
    public function getPDFHelper()
    {
        include_once('helper/PDFHelper.php');
        return new PDFHelper();

    }
}

