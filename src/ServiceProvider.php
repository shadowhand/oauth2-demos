<?php

namespace League\OAuth2\Client\Demo;

use League\Container\ServiceProvider as BaseProvider;

class ServiceProvider extends BaseProvider
{
    protected $provides = [
        'providers',
        'Controller',
    ];

    public function register()
    {
        $this->getContainer()
            ->add('providers', $this->getInstalledProviders());

        $this->getContainer()
            ->add('templates', $this->getTemplatePath());

        $this->getContainer()
            ->add('Plates', 'League\Plates\Engine')
            ->withArgument('templates');

        $this->getContainer()
            ->add('Controller', Controller::class)
            ->withArgument('providers')
            ->withMethodCall('setTemplater', ['Plates']);
    }

    private function getTemplatePath()
    {
        return APP_ROOT . '/templates';
    }

    private function getInstalledProviders()
    {
        $config = json_decode(file_get_contents(APP_ROOT . '/composer.json'));

        $providers = [];
        foreach ($config->require as $package => $version) {
            if (preg_match('/^league\/oauth2-(?!client)(.+)$/', $package, $match)) {
                $providers[] = $match[1];
            }
        }
        return $providers;
    }
}
