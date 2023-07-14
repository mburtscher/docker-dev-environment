<?php

namespace Mburtscher\DockerDevEnvironment\Commands;

use Mburtscher\DockerDevEnvironment\Config\ComposerJson;
use Symfony\Component\Console\Command\Command;

require_once __DIR__.'/../../vendor/autoload.php';

abstract class BaseCommand extends Command
{
    private ?ComposerJson $config = null;

    protected function getProjectIdentifier(): string
    {
        $name = $this->getConfig()->name;

        if (str_contains($name, '/')) {
            $name = substr($name, strpos($name, '/') + 1);
        }

        return strtolower(preg_replace('/[^a-z0-9\-]/', '-', $name));
    }

    protected function getConfig(): ComposerJson
    {
        if ($this->config === null) {
            $this->config = ComposerJson::fromFile('composer.json');
        }

        return $this->config;
    }
}
