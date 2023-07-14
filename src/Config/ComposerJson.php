<?php

namespace Mburtscher\DockerDevEnvironment\Config;

final class ComposerJson
{
    public readonly string $name;
    public readonly PlatformConfig $platform;
    public array $scripts = [];

    public static function fromFile(string $filename): ComposerJson
    {
        return self::fromStdClass(json_decode(file_get_contents($filename)));
    }

    public static function fromStdClass(\stdClass $data): ComposerJson
    {
        $res = new self();

        $res->name = $data->name;
        $res->platform = PlatformConfig::fromStdClass($data?->config?->platform);
        $res->scripts = get_object_vars($data->scripts);

        return $res;
    }
}