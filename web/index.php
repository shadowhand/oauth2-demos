<?php

require __DIR__ . '/../vendor/autoload.php';

if (!is_file($env = __DIR__ . '/../.env')) {
    throw new RuntimeException('Please create a .env file before starting the app!');
}

$injector = new Auryn\Injector;
$env = (new josegonzalez\Dotenv\Loader($env))->parse()->toArray();

(new League\OAuth2\Client\Demo\Configuration)
    ->apply($injector, $env);

$app = Spark\Application::boot($injector);

$app->setMiddleware([
    'Relay\Middleware\ResponseSender',
    'Spark\Handler\ExceptionHandler',
    'Spark\Handler\RouteHandler',
    'League\OAuth2\Client\Demo\ProviderHandler',
    'Spark\Handler\ActionHandler',
]);

$app->addRoutes(function(Spark\Router $r) {
    $r->setDefaultResponder('League\OAuth2\Client\Demo\OAuthResponder');

    $r->get('/', 'League\OAuth2\Client\Demo\Domain\Index');
    $r->get('/connect/{provider}', 'League\OAuth2\Client\Demo\Domain\Connect');
    $r->get('/login/{provider}', 'League\OAuth2\Client\Demo\Domain\Login');
    $r->get('/user/{provider}', 'League\OAuth2\Client\Demo\Domain\User');
});

// We'll need sessions to keep state during authentication.
session_start();

$app->run();
