<?php

// Include Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

use League\OAuth2\Client\Demo\Domain;

Equip\Application::build()
->setConfiguration([
    Equip\Configuration\AurynConfiguration::class,
    Equip\Configuration\DiactorosConfiguration::class,
    Equip\Configuration\EnvConfiguration::class,
    Equip\Configuration\PayloadConfiguration::class,
    Equip\Configuration\PlatesResponderConfiguration::class,
    Equip\Configuration\RelayConfiguration::class,
    Equip\Configuration\SessionConfiguration::class,
    Equip\Configuration\WhoopsConfiguration::class,
    League\OAuth2\Client\Demo\Configuration::class,
])
->setMiddleware([
    Relay\Middleware\ResponseSender::class,
    Equip\Handler\ExceptionHandler::class,
    Equip\Handler\DispatchHandler::class,
    Equip\Handler\JsonContentHandler::class,
    Equip\Handler\FormContentHandler::class,
    League\OAuth2\Client\Demo\ProviderHandler::class,
    Equip\Handler\ActionHandler::class,
])
->setRouting(function (Equip\Directory $directory) {
    return $directory
    ->get('/', Domain\Index::class)
    ->get('/connect/{provider}', Domain\Connect::class)
    ->get('/login/{provider}', Domain\Login::class)
    ->get('/logout/{provider}', Domain\Logout::class)
    ->get('/user/{provider}', Domain\User::class)
    ; // End of routing
})
->run();
