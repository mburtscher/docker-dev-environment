<?php

namespace Mburtscher\DockerDevEnvironment;

use Mburtscher\DockerDevEnvironment\Commands\EnterCommand;
use Mburtscher\DockerDevEnvironment\Commands\StartCommand;
use Mburtscher\DockerDevEnvironment\Commands\StopCommand;

require_once __DIR__.'/../vendor/autoload.php';

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
