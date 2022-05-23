<?php

class SongsController {
    private $printer;
    private $songModel;

    public function __construct($songModel, $printer) {
        $this->songModel = $songModel;
        $this->printer = $printer;
    }

    public function execute() {
        $canciones  = $this->songModel->getCanciones();
        $data = ["canciones" => $canciones];
        $this->printer->generateView('songView.html', $data);
    }

    public function addSongForm(){
        $this->printer->generateView('songFormView.html');
    }

    public function addSong(){
        $input = $_POST["cancion"];
        echo $input;
    }

}