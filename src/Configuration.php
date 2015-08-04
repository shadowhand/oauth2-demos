<?php

namespace League\OAuth2\Client\Demo;

use Auryn\Injector;

class Configuration
{
    public function apply(Injector $injector, array $env)
    {
        $this->applyProviderConfig($injector, $env);

        $injector->define('League\Plates\Engine', [':directory' => __DIR__ . '/../templates']);
    }

    private function applyProviderConfig(Injector $injector, array $env)
    {
        $config = [];
        foreach ($env as $key => $value) {
            list($provider, $key) = explode('_', strtolower($key), 2);
            $key = $this->toCamelCase($key);
            $config[$provider][$key] = $value;
        }

        $injector->define(__NAMESPACE__ . '\\ProviderConfig', [':config' => $config]);
    }

    private function toCamelCase($string)
    {
        $string = str_replace('_', ' ', $string);
        $string = ucwords($string);
        $string = str_replace(' ', '', $string);
        return lcfirst($string);
    }
}
