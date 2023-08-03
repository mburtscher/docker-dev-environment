<?php

namespace Mburtscher\DockerDevEnvironment\Components;

use Mburtscher\DockerDevEnvironment\Config\ComposerJson;

final class AppComponent implements ComponentInterface
{
    private const DOCUMENT_ROOT_PATHS = [
        '.',
        'public',
        'web',
    ];

    private const DEFAULT_COMPOSER_VERSION = 1;

    private string $name;
    private string $version;
    private array $extensions;
    private ?string $setupScript;
    private ?string $documentRoot;
    private int $composerVersion;

    public function __construct(ComposerJson $json)
    {
        $this->name = $json->getNormalizedName();
        $this->version = $json->platform->phpVersion;
        $this->extensions = $json->platform->extensions;
        $this->setupScript = $json->scripts['setup'] ?? null;
        $this->documentRoot = $this->getDocumentRoot();
        $this->composerVersion = $json->platform->composerVersion ?? self::DEFAULT_COMPOSER_VERSION;
    }

    function getEnvironmentVariables(): array
    {
        return [
            'USER_ID' => getmyuid(),
            'DOCUMENT_ROOT' => $this->documentRoot,
            'PHP_VERSION' => $this->version,
            'PHP_EXTENSIONS' => implode(' ', $this->extensions),
            'SETUP_SCRIPT' => $this->setupScript,
            'APP_IMAGE' => $this->name.':'.md5($this->version.implode(' ', $this->extensions)),
            'COMPOSER_VERSION' => $this->composerVersion,
        ];
    }

    function getComposeFiles(): array
    {
        return ['app.yml'];
    }

    private function getDocumentRoot(): ?string
    {
        foreach (self::DOCUMENT_ROOT_PATHS as $path) {
            if (file_exists($path.'/index.php')) {
                return $path;
            }
        }

        return null;
    }
}
