<?php 
namespace App\Crud;

USE \PDOException;
USE \PDO;

use App\Db\Conn;

class Read{
    private $conn;
    private $sql;
    private $result;
    private $places;


    public function __construct(){
            
        $this->conn = new Conn;
        $this->conn = $this->conn->getConnection();
    }

    public function read($table, $fields, $terms = null, $parseString = null){
        if (!empty($parseString)) {
            parse_str($parseString, $this->places);
        }

        $this->sql = "SELECT {$fields} FROM {$table} {$terms}";
        return $this->setExe();;
    }

    public function setPlaces($parseString)
    {
        parse_str($parseString, $this->places);
        $this->setExe();
    }

    public function connect(){
        $this->sql = $this->conn->prepare($this->sql);
        $this->sql->setFetchMode(PDO::FETCH_ASSOC);
    }

    private function getSyn(){
        if ($this->places) {
            foreach ($this->places as $param => $value) {
                if ($param == 'limit' || $param == 'offset') {
                    $value = (int) $value;
                }
                $this->consult->bindValue(":{$param}", $value, (is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR));
            }
        }
    }

    private function setExe(){
        $this -> connect();

        try {
            $this->getSyn();
            $this->sql->execute();
            $this->result = $this->sql->fetchAll();
            return $this->result;
        } catch (PDOException $e) {
            $this->result = null;
            echo "<b>Erro ao Cadastrar:</b> {$e->getMessage()} {$e->getCode()}";
        }
    }
}
?>