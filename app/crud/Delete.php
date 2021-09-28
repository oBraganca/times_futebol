<?php 
namespace App\Crud;

USE \PDOException;
USE \PDO;

use App\Db\Conn;

class Delete
{

    private $table;
    private $terms;
    private $result;
    private $sql;


    private $conn;


    public function __construct(){
            
        $this->conn = new Conn;
        $this->conn = $this->conn->getConnection();
    }

    public function delete($table, $data){
        $this -> table = (string) $table;
        $this -> terms =  $data;

        $this->getSyn();
        $this->setExe();
    }

    public function getRowCount()
    {
        return $this->sql->rowCount();
    }

    private function getSyn(){
        $this->sql = "DELETE FROM {$this->table} {$this->terms}";
    }
    

    private function setExe(){

        try {
            $this->sql = $this->conn->prepare($this->sql);
            $this->sql->execute();
            $this->result = True;
        } catch (PDOException $e) {
            $this->result = null;
            echo "<b>Erro ao Cadastrar:</b> {$e->getMessage()} {$e->getCode()}";
        }
    }

}
?>