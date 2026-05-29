<?php

class Database
{
    private string $host = "127.0.0.1";
    private string $dbName = "password_manager";
    private string $username = "root";
    private string $password = "";
    private ?PDO $connection = null;

    public function connect(): PDO
    {
        if ($this->connection === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";

            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $this->connection;
    }
}