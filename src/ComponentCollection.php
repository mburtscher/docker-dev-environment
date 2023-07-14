<?php

namespace Mburtscher\DockerDevEnvironment;

use Composer\Package\RootPackageInterface;
use Exception;
use Mburtscher\DockerDevEnvironment\Components\AppComponent;
use Mburtscher\DockerDevEnvironment\Components\ComponentInterface;
use Mburtscher\DockerDevEnvironment\Components\MySqlComponent;
use Mburtscher\DockerDevEnvironment\Components\RedisComponent;
use Mburtscher\DockerDevEnvironment\Config\ComposerJson;

require_once __DIR__.'/../vendor/autoload.php';

final class ComponentCollection
{
    /**
     * @var ComponentInterface[]
     */
    private array $components = [];

    public function getAllComposeFiles(): array
    {
        $files = [];

        foreach ($this->components as $component) {
            foreach ($component->getComposeFiles() as $file) {
                $files[] = __DIR__.'/../templates/'.$file;
            }
        }

        return $files;
    }

    public function getAllEnvironmentVariables(): array
    {
        $env = [];

        foreach ($this->components as $component) {
            $env += $component->getEnvironmentVariables();
        }

        return $env;
    }

    private function addComponent(ComponentInterface $component): void
    {
        $this->components[] = $component;
    }

    public static function fromConfig(ComposerJson $json): ComponentCollection
    {
        $res = new self();
        $res->name = $json->getNormalizedName();

        //$res->env['PACKAGES'] = implode(' ', $res->packages);

        // App
        $res->addComponent(new AppComponent($json));

        // MySQL
        if (array_intersect(['mysql', 'mysqli', 'mysqlnd', 'pdo_mysql'], $json->platform->extensions)) {
            $res->addComponent(new MySqlComponent());
        }

        // Redis
        if (array_intersect(['redis'], $json->platform->extensions)) {
            $res->addComponent(new RedisComponent());
        }

        return $res;
    }
}
