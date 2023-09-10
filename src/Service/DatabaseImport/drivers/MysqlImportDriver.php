<?php


namespace App\Service\DatabaseImport\drivers;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use PDO;

class MysqlImportDriver implements DatabaseImportDriver
{
//    private Connection $connection;
    private PDO $connection;

    public function __construct() {
//        $this->connection = $connection;
        $this->connection = new PDO("mysql:host=mysql;dbname=symfony", 'root', '123');
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
        $sql = "CREATE TABLE IF NOT EXISTS `events` (
                `id` int NOT NULL AUTO_INCREMENT,
                `ctime` datetime NOT NULL,
                `event_name` varchar(255) NOT NULL,
                PRIMARY KEY(id));";

        $this->connection->exec($sql);
    }
}