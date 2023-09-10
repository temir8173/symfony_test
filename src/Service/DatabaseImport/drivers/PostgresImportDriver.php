<?php


namespace App\Service\DatabaseImport\drivers;


use PDO;

class PostgresImportDriver implements DatabaseImportDriver
{
    private PDO $connection;

    public function __construct() {
        $this->connection = new PDO("pgsql:host=postgres;dbname=symfony", 'postgres', 'postgres');
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->createTable();
    }

    public function importData(array $data): void
    {
        $sql = "INSERT INTO events (id, ctime, event_name) VALUES (?,?,?)";
        $this->connection
            ->prepare($sql)
            ->execute([
                $data['uuid'],
                $data['ctime'],
                $data['eventName'],
            ]);
    }

    private function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS events (
                    id serial PRIMARY KEY,
                    ctime timestamp NOT NULL,
                    event_name VARCHAR(255) NOT NULL
                );";

        $this->connection->exec($sql);
    }
}
