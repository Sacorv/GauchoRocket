<?php

class MySqlDatabase {

    private $host;
    private $user;
    private $pass;
    private $database;

    private $conn;

    public function __construct($host, $user, $pass, $database) {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->database = $database;

        $this->connect();
    }

    public function __destruct(){
        $this->disconnect();
    }


    private function connect() {
        $conn = mysqli_connect($this->host, $this->user, $this->pass, $this->database);
        if (!$conn) {
            die('Connection failed: ' . mysqli_connect_error());
        }
        $this->conn = $conn;
    }

    public function query($sql) {
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result , MYSQLI_ASSOC);
    }
    
    //HACER: Validar mail contra la DB
    public function create($firstName, $lastName, $dni, $email, $password) {
        $sql = "insert into usuario (nombre, apellido, dni, email, tipo, password) values(?,?,?,?,?,?)";
        $query = $this->conn->prepare($sql);
        $tipo = 2;
        $query->bind_param("ssisis", $firstName, $lastName, $dni, $email, $tipo, $password);
        $query->execute();
        return $query;
    }

    public function validarMail($mail){
        $sqlquery = "select * from usuario where email='$mail'";
        $result = mysqli_query($this->conn, $sqlquery);
        return mysqli_num_rows($result);


    }
    public function login($mail , $password){

        $password = md5($password);
        $query = $this->conn->prepare("select * from usuario where email= ? and password = ?");
        $query->bind_param("ss", $mail , $password);
        $result = $query->execute();
        $query->store_result();
       return $query->num_rows;



    }


    private function disconnect() {
        mysqli_close($this->conn);
    }
}