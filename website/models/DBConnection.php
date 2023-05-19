<?php
class DBConnection
{
    public PDO $connection;

    public function __construct($host, $username, $password, $database)
    {
        $this->connection = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
    }

    public function query($sql, $params = []): array
    {
        $statement = $this->connection->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function execute($sql, $params = []): bool
    {
        $statement = $this->connection->prepare($sql);
        return $statement->execute($params);
    }
}