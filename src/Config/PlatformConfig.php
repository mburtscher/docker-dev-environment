<?php

namespace Mburtscher\DockerDevEnvironment\Config;

use stdClass;

final class PlatformConfig
{
    public readonly ?string $phpVersion;
    public array $extensions = [];
    public readonly ?int $composerVersion;

    public static function fromStdClass(?stdClass $data): PlatformConfig
    {
        $res = new self();
        if ($data === null) {
            return $res;
        }

        $res->phpVersion = $data->php;

        foreach (get_object_vars($data) as $key => $value) {
            if (str_starts_with($key, 'ext-')) {
                $res->extensions[] = substr($key, 4);
            }
        }

        if (isset($data->composer)) {
            $res->composerVersion = (int)$data->composer;
        } else {
            $res->composerVersion = null;
        }

        return $res;
    }
}