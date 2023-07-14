<?php

namespace Mburtscher\DockerDevEnvironment\Components;

interface ComponentInterface
{
    function getEnvironmentVariables(): array;
    function getComposeFiles(): array;
}