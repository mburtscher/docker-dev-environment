<?php

namespace Mburtscher\DockerDevEnvironment\Components;

final class MySqlComponent implements ComponentInterface
{
    private string $version = '8.0';
    private string $user = 'app';
    private string $password = 'app';
    private string $name = 'app';

    function getEnvironmentVariables(): array
    {
        return [
            'DB_HOST' => 'mysql',
            'DB_USER' => $this->user,
            'DB_PASSWORD' => $this->password,
            'DB_NAME' => $this->name,
            'DATABASE_URL' => 'mysql://'.$this->user.':'.$this->password.'@mysql:3306/'.$this->name.'?serverVersion='.$this->version,
        ];
    }

    function getComposeFiles(): array
    {
        return ['mysql.yml'];
    }
}
