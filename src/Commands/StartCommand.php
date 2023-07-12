<?php

namespace Mburtscher\DockerDevEnvironment\Commands;

use Exception;
use Mburtscher\DockerDevEnvironment\ComposeCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class StartCommand extends BaseCommand
{

    protected function configure()
    {
        $this->setName('start');
        $this->setDescription('Starts the Docker development environment.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $command = ComposeCommand::fromPackage($this->getApplication()->getComposer()->getPackage());
        } catch (Exception $ex) {
            $output->writeln('<error>'.$ex->getMessage().'</error>');
            return self::FAILURE;
        }

        $output->writeln('Starting environment…');

        $process = new Process($command->getUpCommand(), null, $command->getUpEnvironment());
        $process->run(fn ($type, $buffer) => $output->write($buffer));

        return self::SUCCESS;
    }
}
