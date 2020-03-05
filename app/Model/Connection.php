<?php

class Connection {

    private $conn;

    public function __construct()
    {
        $this->conn = new PDO("mysql:host=localhost;dbname=campotv", "root", "");
        $this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        var_dump('dasdasd');
    }

    public function conn()
    {
        return $this->conn;
    }
}