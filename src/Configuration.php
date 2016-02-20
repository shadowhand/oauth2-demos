<?php

namespace League\OAuth2\Client\Demo;

use Auryn\Injector;
use Equip\Env;
use Equip\Configuration\ConfigurationInterface;
use League\Plates\Engine;

class Configuration implements ConfigurationInterface
{
    /**
     * @var Env
     */
    private $env;

    public function __construct(Env $env)
    {
        $this->env = $env;
    }

    public function apply(Injector $injector)
    {
        $injector->define(Engine::class, [
            ':directory' => __DIR__ . '/../templates',
        ]);

        $config = $this->getProviderConfig();

        $injector->define(ProviderHandler::class, [
            ':injector' => $injector,
            ':config' => $config,
        ]);

        $injector->share($config);
    }

    private function getProviderConfig()
    {
        $config = [];
        foreach ($this->env as $key => $value) {
            list($provider, $key) = explode('_', strtolower($key), 2);
            $key = $this->toCamelCase($key);
            $config[$provider][$key] = $value;
        }

        return new ProviderConfig($config);
    }

    private function toCamelCase($string)
    {
        $string = str_replace('_', ' ', $string);
        $string = ucwords($string);
        $string = str_replace(' ', '', $string);
        return lcfirst($string);
    }
}
