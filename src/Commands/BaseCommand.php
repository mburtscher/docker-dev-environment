<?php

namespace Mburtscher\DockerDevEnvironment\Commands;

use Mburtscher\DockerDevEnvironment\Config\ComposerJson;
use Symfony\Component\Console\Command\Command;

require_once __DIR__.'/../../vendor/autoload.php';

abstract class BaseCommand extends Command
{
    private ?ComposerJson $config = null;

    protected function getStackName(): string
    {
        return $this->getConfig()->getNormalizedName();
    }

    protected function getConfig(): ComposerJson
    {
        if ($this->config === null) {
            $this->config = ComposerJson::fromFile('composer.json');
        }

        return $this->config;
    }
}
