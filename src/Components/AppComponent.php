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

    private string $name;
    private string $phpVersion;
    private array $phpExtensions;
    private ?string $setupScript;
    private ?string $documentRoot;

    public function __construct(ComposerJson $json)
    {
        $this->name = $json->getNormalizedName();
        $this->phpVersion = $json->platform->phpVersion;
        $this->phpExtensions = $json->platform->extensions;
        $this->setupScript = $json->scripts['setup'] ?? null;
        $this->documentRoot = $this->getDocumentRoot();
    }

    function getEnvironmentVariables(): array
    {
        return [
            'USER_ID' => getmyuid(),
            'DOCUMENT_ROOT' => $this->documentRoot,
            'PHP_VERSION' => $this->phpVersion,
            'PHP_EXTENSIONS' => implode(' ', $this->phpExtensions),
            'SETUP_SCRIPT' => $this->setupScript,
            'APP_IMAGE' => $this->name.':'.md5($this->phpVersion.implode(' ', $this->phpExtensions)),
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
