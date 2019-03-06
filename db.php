<?php

class DB
{
    private static $instance = null;
    private $pdo;
    private $config = [
        'dsn' => 'mysql:host=localhost;dbname=review;charset=utf8',
        'user' => 'root',
        'password' => '12345678'
    ];

    private function __construct()
    {
        try {
            $this->pdo = new \PDO($this->config['dsn'], $this->config['user'], $this->config['password']);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\Exception $e) {
            throw new \Exception('Произошла ошыбка при сохранении!');
        }
    }

    private function __clone(){}

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    public function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }

    public function commit()
    {
        $this->pdo->commit();
    }

    public function rollBack()
    {
        $this->pdo->rollBack();
    }
}