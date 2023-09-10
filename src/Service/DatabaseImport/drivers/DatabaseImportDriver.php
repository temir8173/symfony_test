<?php


namespace App\Service\DatabaseImport\drivers;


interface DatabaseImportDriver
{
    public function importData(array $data): void;
}
