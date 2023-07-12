<?php

namespace Mburtscher\DockerDevEnvironment;

use Composer\Command\BaseCommand;
use Mburtscher\DockerDevEnvironment\Commands\EnterCommand;
use Mburtscher\DockerDevEnvironment\Commands\StartCommand;
use Mburtscher\DockerDevEnvironment\Commands\StopCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommandProvider implements \Composer\Plugin\Capability\CommandProvider
{
    public function getCommands()
    {
        return [
            new StartCommand(),
            new StopCommand(),
            new EnterCommand(),
        ];
    }
}
