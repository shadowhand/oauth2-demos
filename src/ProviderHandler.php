<?php

namespace League\OAuth2\Client\Demo;

use Auryn\Injector;
use League\OAuth2\Client\Provider\AbstractProvider;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use RuntimeException;

class ProviderHandler
{
    private $injector;
    private $config;

    public function __construct(Injector $injector, ProviderConfig $config)
    {
        $this->injector = $injector;
        $this->config = $config;
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        $provider = $request->getAttribute('provider');

        if ($provider) {
            if (empty($this->config[$provider])) {
                throw new RuntimeException(sprintf(
                    'Provider "%s" has not been defined, check your configuration',
                    $provider
                ));
            }

            $config = $this->config[$provider];

            if (empty($config['redirectUri'])) {
                // Convert the current URL into the login URL for this provider.
                $uri = $request->getUri()->withPath('/login/' . $provider)->withQuery('');

                $config['redirectUri'] = (string) $uri;
            }

            $alias = 'League\\OAuth2\\Client\\Provider\\AbstractProvider';
            $class = $config['provider'];

            $this->injector->alias(AbstractProvider::class, $class);
            $this->injector->define($class, [':options' => $config]);
        }

        return $next($request, $response);
    }
}
