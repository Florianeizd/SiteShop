<?php

namespace App\Command;

use App\Service\Imaginary\ImaginaryClient;
use App\Service\Imaginary\ImaginaryServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'imaginary:health',
    description: 'Add a short description for your command',
)]
class ImaginaryCommand extends Command
{
    private ImaginaryServiceInterface $imaginaryService;
    private string $imaginaryUrl;

    /**
     * @param string|null $name
     * @param ImaginaryServiceInterface $imaginaryService
     * @param string $imaginaryUrl
     */
    public function __construct(string $name = null, ImaginaryServiceInterface $imaginaryService, string $imaginaryUrl)
    {
        parent::__construct($name);
        $this->imaginaryService = $imaginaryService;
        $this->imaginaryUrl = $imaginaryUrl;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $client = new ImaginaryClient();
        $client->setServiceUri($this->imaginaryUrl);

        $imaginary = $this->imaginaryService->health();

        $io->info('Etat de santé sur l\'api Imaginary : ');
        $io->writeln('- Temps de fonctionnement du processus du serveur en secondes : ' .$imaginary['uptime'] .'s');
        $io->writeln('- Mémoire actuellement allouée en mégaoctets : ' .$imaginary['allocatedMemory'] .'Mo');
        $io->writeln('- Total Allocated Memory : ' .$imaginary['totalAllocatedMemory'] .'Mo');
        $io->writeln('- Nombre de thread en cours d\'exécution : ' .$imaginary['goroutines']);
        $io->writeln('- Nombre de cœurs de CPU utilisés : ' .$imaginary['cpus']);

        $io->newLine();

        return Command::SUCCESS;
    }
}
