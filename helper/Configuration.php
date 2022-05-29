<?php
include_once('helper/MySqlDatabase.php');
include_once('helper/Router.php');
require_once('helper/MustachePrinter.php');
include_once('controller/SongsController.php');
include_once('controller/ToursController.php');
include_once('controller/DestinosController.php');
include_once('controller/VuelosController.php');
include_once('model/SongModel.php');
include_once('model/TourModel.php');
include_once ('model/HomeModel.php');
include_once ('model/VuelosModel.php');
require_once('third-party/mustache/src/Mustache/Autoloader.php');
include_once('controller/UserController.php');
include_once('model/UserModel.php');

class Configuration {
    public function getUserController() {
        return new UserController($this->getUserModel(), $this->getPrinter());
    }

    private function getUserModel() {
        return new UserModel($this->getDatabase());
    }

    public function getVuelosController() {
        return new VuelosController($this->getVuelosModel(), $this->getPrinter());
    }
    private function getVuelosModel() {
        return new VuelosModel($this->getDatabase());
    }
    public function getDestinosController(){
        return new DestinosController($this->getHomeModel(),$this->getPrinter());
    }
    private function getHomeModel() {
        return new HomeModel($this->getDatabase());
    }

//    -----------------------------------------------------------------------------

    public function getSongsController() {
        return new SongsController($this->getSongModel(), $this->getPrinter());
    }

    public function getToursController() {
        return new ToursController($this->getTourModel(), $this->getPrinter());
    }

    private function getSongModel(){
        return new SongModel($this->getDatabase());
    }

    private function getTourModel() {
        return new TourModel($this->getDatabase());
    }

    private function getDatabase() {
       return new MySqlDatabase('localhost','root','2203yba','gaucho_rocket');
    }

    private function getPrinter() {
        return new MustachePrinter("view");
    }

    public function getRouter() {
        return new Router($this, "getDestinosController", "execute");
    }


}