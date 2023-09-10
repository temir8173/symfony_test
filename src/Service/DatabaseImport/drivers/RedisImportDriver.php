<?php


namespace App\Service\DatabaseImport\drivers;


use Redis;
use RedisException;

class RedisImportDriver implements DatabaseImportDriver
{
    private Redis $connection;

    public function __construct() {
        $this->connection = new Redis();

        $this->connection->connect('redis');
//        $redis->auth('password');
    }

    public function importData(array $data): void
    {
        $this->connection->set($data['uuid'], serialize($data));
    }
}
