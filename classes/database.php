<?php 
class Database {    
    private $pdo;
    private $host = "localhost";
    private $name = "3c";
    private $user = "root";
    private $password = "";

    public function __construct() {
    
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->name", $this->user, $this->password);
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }

    }

    public function getPDO() {
        return $this->pdo;
    }
}