<?php 
namespace App\Crud;

USE \PDOException;
USE \PDO;

use App\Db\Conn;

class Delete
{

    /* Atributos privada */
    private $table;
    private $terms;
    private $result;
    private $sql;


    private $conn;

    /* Connection com o banco */
    public function __construct(){
            
        $this->conn = new Conn;
        $this->conn = $this->conn->getConnection();
    }

    /* Será o nosso metodo publico que ira receber a tabela e o termo */
    public function delete($table, $data){
        $this -> table = (string) $table;
        $this -> terms =  $data;

        $this->getSyn();
        $this->setExe();
    }

    /* Um metodo que retorna a quantidade de linhas apagadas */
    public function getRowCount()
    {
        return $this->sql->rowCount();
    }

    /* Criamos nossa tabela */
    private function getSyn(){
        $this->sql = "DELETE FROM {$this->table} {$this->terms}";
    }
    

    private function setExe(){

        try {
            /* Preparamos a query e executamos */
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