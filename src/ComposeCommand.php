<?php

namespace Mburtscher\DockerDevEnvironment;

use Composer\Package\RootPackageInterface;
use Exception;

final class ComposeCommand
{
    private const DOCUMENT_ROOT_PATHS = [
        '.',
        'public',
        'web',
    ];

    private string $name;
    private array $env = [];
    private array $composeFiles = [
        'app.yml',
    ];

    public function getUpCommand(): array
    {
        $cmd = ['docker', 'compose', '-p', $this->name];

        foreach ($this->composeFiles as $file) {
            $cmd[] = '-f';
            $cmd[] = __DIR__.'/../templates/'.$file;
        }

        $cmd[] = 'up';
        $cmd[] = '-d';

        return $cmd;
    }

    public function getUpEnvironment(): array
    {
        return $this->env;
    }

    public function getDownCommand(): array
    {
        return ['docker', 'compose', '-p', $this->name, 'down'];
    }

    public static function fromPackage(RootPackageInterface $package): ComposeCommand
    {
        $res = new self();

        $res->name = self::getName($package);

        $res->env['ROOT_DIR'] = getcwd();
        $res->env['DOCUMENT_ROOT'] = self::getDocumentRoot();
        $res->env['PHP_VERSION'] = self::getPhpVersion($package);
        $res->env['PHP_EXTENSIONS'] = implode(' ', ($extensions = self::getPhpExtensions($package)));

        // MySQL
        if (in_array('mysql', $extensions) || in_array('mysli', $extensions) || in_array('pdo_mysql', $extensions)) {
            $res->composeFiles[] = 'mysql.yml';
        }

        return $res;
    }


    private static function getName(RootPackageInterface $package): string
    {
        $name = $package->getName();

        if (str_contains($name, '/')) {
            $name = substr($name, strpos($name, '/') + 1);
        }

        return strtolower(preg_replace('/[^a-z0-9\-]/', '-', $name));
    }

    private static function getDocumentRoot(): string
    {
        foreach (self::DOCUMENT_ROOT_PATHS as $path) {
            if (file_exists($path.'/index.php')) {
                return $path;
            }
        }

        throw new Exception('Document root could not be found. Are you missing a index.php file?');
    }

    private static function getPhpVersion(RootPackageInterface $package): string
    {
        $config = $package->getConfig();

        if (isset($config['platform'], $config['platform']['php'])) {
            return $config['platform']['php'];
        }

        throw new Exception('PHP version could not befound. Are you missing a config.platform option in your composer.json?');
    }



    private static function getPhpExtensions(RootPackageInterface $package): array
    {
        $config = $package->getConfig();

        $res = [];

        foreach (($config['platform'] ?? []) as $key => $value) {
            if (str_starts_with($key, 'ext-')) {
                $res[] = substr($key, 4);
            }
        }

        return $res;
    }
}