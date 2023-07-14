<?php

namespace Mburtscher\DockerDevEnvironment\Components;

final class RedisComponent implements ComponentInterface
{
    function getEnvironmentVariables(): array
    {
        return [
            'REDIS_HOST' => 'redis',
        ];
    }

    function getComposeFiles(): array
    {
        return ['redis.yml'];
    }
}
