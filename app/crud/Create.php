<?php 
/* Namespace para usar nos arquivos php */
namespace App\Crud;

USE \PDOException;
USE \PDO;

/* Permite que instaciamos uma classe Conn */
use App\Db\Conn;

class Create{
    /* Atributos importantes definido como privates */
    private $conn;
    private $sql;
    private $result;

    /* Nosso construct responsavel por settar o $conn com a connection do Conn class */
    public function __construct(){
            
        $this->conn = new Conn;
        $this->conn = $this->conn->getConnection();
    }

    /* Recebe o a tabela e os dados como array */
    public function create($table, array $data){
        $this -> table = (string) $table;
        $this -> data =  $data;
        $this->getSyn();
        $this->setExe();
    }

    private function getSyn(){
        /* Criamos a sintaxe para usar o prepare do pdo
            Onde os places receberção as keys com ?? ou :name_attribute
        */
        $fields = implode(', ', array_keys($this->data));
        $places = ':' . implode(', :', array_keys($this->data));
        
        $this->sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$places})";
    }

    private function setExe(){

        try {
            /* Preparamos a query sql com o prepare() que ajuda no desempenho e previnir sql injection
                execute() com a data, vai substituir o :name_attribute ou o ??.
            */
            $this->sql = $this->conn->prepare($this->sql);
            $this->sql->execute($this->data);
            $this->result = $this->conn->lastInsertId(); /* Retorna o id a ultima linha inserida no banco */
        } catch (PDOException $e) {
            $this->result = null;
            echo "<b>Erro ao Cadastrar:</b> {$e->getMessage()} {$e->getCode()}";
        }
    }
}
?>