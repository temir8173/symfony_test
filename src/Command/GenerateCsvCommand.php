<?php


namespace App\Command;


use DateTime;
use Generator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'generate-csv')]
class GenerateCsvCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setDescription('Import data from a file into a database')
            ->addArgument('filepath', InputArgument::REQUIRED, 'Path to the CSV file')
            ->addArgument('uuidfrom', InputArgument::REQUIRED, 'First uuid')
            ->addArgument('qty', InputArgument::REQUIRED, 'Quntity of rows');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filepath = $input->getArgument('filepath');
        $uuidfrom = (int)$input->getArgument('uuidfrom');
        $qty = (int)$input->getArgument('qty');

        $dataGenerator =$this->generateData($qty, $uuidfrom);

        $handle = fopen($filepath, "wb");
        fputcsv($handle, ['uuid', 'ctime', 'eventName'], ';');
        foreach ($dataGenerator as $data) {
            fputcsv($handle, $data, ';');
        }
        fclose($handle);

        $output->writeln('Data imported successfully.');
        return Command::SUCCESS;
    }

    private function generateData(int $qty, int $uuidfrom): Generator
    {
        $uuid = $uuidfrom;
        $ctime = '2023-12-31 23:59:00'; // в целях экономии времени не стал делать рандомно

        while ($qty > 0) {
            $eventName = $this->generateRandomString(32);
            $ctime = $this->generateRandomDatetime();
            yield [
                'uuid' => $uuid,
                'ctime' => $ctime,
                'eventName' => $eventName
            ];

            $qty--;
            $uuid++;
        }
    }

    private function generateRandomString(int $strlength = 16): string
    {
        $input = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $inputLength = strlen($input);
        $randomString = '';
        for($i = 0; $i < $strlength; $i++) {
            $randomCharacter = $input[mt_rand(0, $inputLength - 1)];
            $randomString .= $randomCharacter;
        }

        return $randomString;
    }

    private function generateRandomDatetime(): string
    {
        $timestamp = mt_rand(1262055681, 1694432146);
        return date("Y-m-d H:i:s", $timestamp);
    }

}