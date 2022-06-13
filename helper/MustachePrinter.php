<?php

class MustachePrinter {

    private $mustache;
    private $viewPath;
    private LoginHelper $helper;
    public function __construct($viewPath,$helper){
        $this->viewPath = $viewPath;
        Mustache_Autoloader::register();
        $this->helper=$helper;
        $this->mustache = new Mustache_Engine(
            [
                'partials_loader' => new Mustache_Loader_FilesystemLoader( $viewPath )
            ]);
    }

    public function generateView($template , $data = []){
        $contentAsString =  file_get_contents($this->viewPath . "/" .$template);
        $data+=$this->helper->executeLogin();
        echo $this->mustache->render($contentAsString, $data);
    }

}