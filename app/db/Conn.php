<?php
namespace App\Db;

USE \PDOException;
USE \PDO;

class Conn{
    /*
        Constantes do banco, como estamos usando o phpmyadmin, deixei com a minhas configurações
        rodando no localhost
        user root
        sem password
        e o nome escolhido por mim
    */
    const HOST = 'localhost';
    const USER = 'root';
    const PASS = '';
    const NAME = 'remopt';

    
    /* Propriedade privada */
    private $connection;

    /* Um metodo publico que será usado pelas classes do crud
        é publica porque será nessesario a connection com o banco
    */
    public function getConnection(){
        return $this->setConnection();
    }

    private function setConnection(){
        /* Try catch para fazer a connection, usando o pdo para  */
        try {
            $this -> connection = new PDO('mysql:host='.self::HOST.';dbname='.self::NAME, self::USER, self::PASS);
            /* Setando um relatorio de erro, e o valor exception para usar o PDOException */
            $this -> connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('ERROR: '.$e -> getMessage());
        }
        /* Retorna a conexão */
        return $this->connection;
    }
}
?>