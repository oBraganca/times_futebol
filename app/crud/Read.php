<?php 
namespace App\Crud;

USE \PDOException;
USE \PDO;

use App\Db\Conn;

class Read{
    /* Atributos privados */
    private $conn;
    private $sql;
    private $result;
    private $places;

    /* Ponte com o banco de dados */
    public function __construct(){
            
        $this->conn = new Conn;
        $this->conn = $this->conn->getConnection();
    }
    /* Metodo que vai auxiliar na criação

    */
    public function read($table, $fields, $terms = null, $parseString = null){
        /* Convewrse a string em variavel */
        if (!empty($parseString)) {
            parse_str($parseString, $this->places);
        }

        /* Criamos o esboço da nossa query com oque queremos retornar a table e o parametro */
        $this->sql = "SELECT {$fields} FROM {$table} {$terms}";
        return $this->setExe();;
    }


    private function connect(){
        $this->sql = $this->conn->prepare($this->sql);
        /* O PDO retorna uma matriz com associação */
        $this->sql->setFetchMode(PDO::FETCH_ASSOC);
    }

    private function setExe(){
        /* chamamos o metodo que vai fazer a preparação */
        $this -> connect();

        try {
            /*Executa a query */
            $this->sql->execute();
            /* Retorna a matriz com o conjunto de dados */
            $this->result = $this->sql->fetchAll();
            return $this->result;
        } catch (PDOException $e) {
            $this->result = null;
            echo "<b>Erro ao Cadastrar:</b> {$e->getMessage()} {$e->getCode()}";
        }
    }
}
?>