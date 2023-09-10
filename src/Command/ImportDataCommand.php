<?php

namespace App\Command;

use App\Service\DatabaseImport\DataImporterService;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'import')]
class ImportDataCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setDescription('Import data from a file into a database')
            ->addArgument('filepath', InputArgument::REQUIRED, 'Path to the CSV file')
            ->addArgument('to', InputArgument::REQUIRED, 'Database type (redis, postgres, or mysql)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filepath = $input->getArgument('filepath');
        $database = $input->getArgument('to');

        try {
            $importerService = new DataImporterService($database);
            $importerService->process($filepath);
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
            return Command::FAILURE;
        }

        $output->writeln('Data imported successfully.');
        return Command::SUCCESS;
    }
}
