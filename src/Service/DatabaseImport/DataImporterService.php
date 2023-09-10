<?php


namespace App\Service\DatabaseImport;


use App\Service\DatabaseImport\drivers\DatabaseImportDriver;
use App\Service\DatabaseImport\drivers\MysqlImportDriver;
use App\Service\DatabaseImport\drivers\PostgresImportDriver;
use App\Service\DatabaseImport\drivers\RedisImportDriver;
use Exception;

class DataImporterService
{
    private DatabaseImportDriver $driver;

    public function __construct(string $database)
    {
        $this->setDriver($database);
    }

    public function setDriver(string $database)
    {
        $driverClass = match ($database) {
            'redis' => RedisImportDriver::class,
            'mysql' => MysqlImportDriver::class,
            'postgres' => PostgresImportDriver::class,
        };

        $this->driver = new $driverClass();
    }

    /**
     * @throws Exception
     */
    public function process(string $filepath)
    {
        $generator = $this->readCsv($filepath, ';');

        if (!$generator) {
            throw new Exception('Incorrect filename');
        }

        foreach ($generator as $item) {
            var_dump($item);
            $this->driver->importData($item);
        }

    }

    private function readCsv($filename, $delimiter = ','): bool|\Generator
    {
        $header = [];
        $row = 0;
        $handle = fopen($filename, "r");

        if ($handle === false) {
            return false;
        }

        while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
            if (0 == $row) {
                $header = $data;
            } else {
                yield array_combine($header, $data);
            }
            $row++;
        }
        fclose($handle);
    }
}
