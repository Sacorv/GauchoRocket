<?php

class VuelosModel
{


    private MySqlDatabase $getDatabase;

    public function __construct( $getDatabase)
    {
        $this->getDatabase = $getDatabase;
    }
}