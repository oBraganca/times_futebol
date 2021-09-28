<?php
namespace App\Db;

USE \PDOException;
USE \PDO;

class Conn{
    const HOST = 'localhost';
    const USER = 'root';
    const PASS = '';
    const NAME = 'remopt';

    // private $table;
    
    private $connection;

    public function getConnection(){
        return $this->setConnection();
    }

    private function setConnection(){
        try {
            $this -> connection = new PDO('mysql:host='.self::HOST.';dbname='.self::NAME, self::USER, self::PASS);
            $this -> connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('ERROR: '.$e -> getMessage());
        }
        return $this->connection;
    }
}
?>