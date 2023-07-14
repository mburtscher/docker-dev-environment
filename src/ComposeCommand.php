<?php

namespace Mburtscher\DockerDevEnvironment;

use Composer\Package\RootPackageInterface;
use Exception;
use Mburtscher\DockerDevEnvironment\Config\ComposerJson;

require_once __DIR__.'/../vendor/autoload.php';

final class ComposeCommand
{
    private const DOCUMENT_ROOT_PATHS = [
        '.',
        'public',
        'web',
    ];

    private const DB_USER = 'app';
    private const DB_PASSWORD = 'app';
    private const DB_NAME = 'app';

    private string $name;
    private array $env = [
        'ROOT_DIR' => null,
        'DOCUMENT_ROOT' => null,
        'PHP_VERSION' => null,
        'PHP_EXTENSIONS' => null,
        'PACKAGES' => null,
        // Redis
        'REDIS_HOST' => null,
        // MySQL
        'DB_HOST' => null,
        'DB_USER' => null,
        'DB_PASSWORD' => null,
        'DB_NAME' => null,
        'DATABASE_URL' => null,
        'MYSQL_VERSION' => '8.0',
    ];
    private array $composeFiles = [
        'app.yml',
    ];

    private array $packages = [];

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

    public static function fromConfig(ComposerJson $json): ComposeCommand
    {
        $res = new self();

        $res->name = self::getName($json);

        $res->env['USER_ID'] = getmyuid();
        $res->env['ROOT_DIR'] = getcwd();
        $res->env['DOCUMENT_ROOT'] = self::getDocumentRoot();
        $res->env['PHP_VERSION'] = $json->platform->phpVersion;
        $res->env['PHP_EXTENSIONS'] = implode(' ', $json->platform->extensions);

        // Setup script
        if (isset($json->scripts['setup'])) {
            $res->env['SETUP_SCRIPT'] = 'setup';
        }

        self::addMySql($res, $json->platform->extensions);
        self::addRedis($res, $json->platform->extensions);

        $res->env['PACKAGES'] = implode(' ', $res->packages);

        // Generate a unique app image hash to force rebuild if config changes
        $res->env['APP_IMAGE'] = $res->name.':'.md5($res->env['PHP_VERSION'].$res->env['PHP_EXTENSIONS'].$res->env['PACKAGES']);

        return $res;
    }


    private static function getName(ComposerJson $package): string
    {
        $name = $package->name;

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

    private static function addMySql(self $res, array $extensions): void
    {
        if (!array_intersect(['mysql', 'mysqli', 'mysqlnd', 'pdo_mysql'], $extensions)) {
            return;
        }

        $res->composeFiles[] = 'mysql.yml';
        $res->packages[] = 'default-mysql-client';

        $res->env['DB_HOST'] = 'mysql';
        $res->env['DB_USER'] = self::DB_USER;
        $res->env['DB_PASSWORD'] = self::DB_PASSWORD;
        $res->env['DB_NAME'] = self::DB_NAME;

        // Symfony
        $res->env['DATABASE_URL'] = 'mysql://'.self::DB_USER.':'.self::DB_PASSWORD.'@mysql:3306/'.self::DB_NAME.'?serverVersion='.$res->env['MYSQL_VERSION'];
    }

    private static function addRedis(self $res, array $extensions): void
    {
        if (!array_intersect(['redis'], $extensions)) {
            return;
        }

        $res->composeFiles[] = 'redis.yml';

        $res->env['REDIS_HOST'] = 'redis';
    }
}