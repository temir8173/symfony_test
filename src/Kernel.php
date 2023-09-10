<?php

namespace App;

use App\Command\GenerateCsvCommand;
use App\Command\ImportDataCommand;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Console\Application;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureCommands(Application $application)
    {
        $application->addCommands([
            // ...
            new ImportDataCommand(),
            new GenerateCsvCommand(),
        ]);
    }
}
