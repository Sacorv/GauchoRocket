<?php

class Printer {

    public function __construct() {
    }

    public function generateView($content, $data = []  ) {
        include_once('view/header.mustache');
        include_once('view/navbar.mustache');
        include_once("view/" . $content);
        include_once('view/footer.mustache');
        
    }
}