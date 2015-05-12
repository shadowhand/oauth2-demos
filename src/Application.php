<?php

namespace League\OAuth2\Client\Demo;

use Proton\Application as BaseApplication;

class Application extends BaseApplication
{
    public function __construct($debug = true)
    {
        parent::__construct($debug);

        $routes = [
            '/'                    => 'index',
            '/login/{provider}'    => 'login',
            '/complete/{provider}' => 'complete',
        ];
        foreach ($routes as $url => $action) {
            $this->get($url, 'Controller::' . $action);
        }

        $this->register(ServiceProvider::class);
    }
}
