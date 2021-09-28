<?php 
namespace App\Crud;

USE \PDOException;
USE \PDO;

use App\Db\Conn;

class Create{
    private $conn;
    private $sql;
    private $result;


    public function __construct(){
            
        $this->conn = new Conn;
        $this->conn = $this->conn->getConnection();
    }
    public function create($table, array $data){
        $this -> table = (string) $table;
        $this -> data =  $data;
        $this->getSyn();
        $this->setExe();
    }

    private function getSyn(){
        $fields = implode(', ', array_keys($this->data));
        $places = ':' . implode(', :', array_keys($this->data));
        
        $this->sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$places})";
    }

    private function setExe(){

        try {
            $this->sql = $this->conn->prepare($this->sql);
            $this->sql->execute($this->data);
            $this->result = $this->conn->lastInsertId();
        } catch (PDOException $e) {
            $this->result = null;
            echo "<b>Erro ao Cadastrar:</b> {$e->getMessage()} {$e->getCode()}";
        }
    }
}
?>