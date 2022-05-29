<?php
include_once ("helper/Configuration.php");
session_start();
//muestro el login

if(isset($_SESSION["user"]) && $_SESSION["bienvenida"] == true){
    echo " <div> <h2>Bienvenido ". $_SESSION['user'] . "</h2> </div> ";
}else{

}
$configuration = new Configuration();
$router = $configuration->getRouter();

//http://www.labanda.com/songs/addSong
//http://www.labanda.com/index.php?controller=songs&method=addSong


$controller = isset($_GET["controller"])? $_GET['controller'] : "" ;
$method = isset($_GET["method"])? $_GET['method'] : "";

$router->executeMethodFromController($controller, $method);