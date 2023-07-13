<?php

namespace Mburtscher\DockerDevEnvironment\Commands;

require_once __DIR__.'/../../vendor/autoload.php';

abstract class BaseCommand extends \Composer\Command\BaseCommand
{
    protected function getProjectIdentifier(): string
    {
        $name = $this->getApplication()->getComposer()->getPackage()->getName();

        if (str_contains($name, '/')) {
            $name = substr($name, strpos($name, '/') + 1);
        }

        return strtolower(preg_replace('/[^a-z0-9\-]/', '-', $name));
    }
}
