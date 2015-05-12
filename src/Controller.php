<?php

namespace League\OAuth2\Client\Demo;

use League\Plates\Engine;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use RuntimeException;

class Controller
{
    /**
     * @var array
     */
    protected $providers;

    /**
     * @var Engine
     */
    protected $templates;

    public function __construct(array $providers)
    {
        $this->providers = $providers;
    }

    public function setTemplater(Engine $templates)
    {
        $this->templates = $templates;
    }

    private function getProvider($provider, $redirectUri = null)
    {
        $config = $this->getConfig($provider) + compact('redirectUri');

        $class  = 'League\\OAuth2\\Client\\Provider\\' . ucfirst($provider);
        return new $class($config);
    }

    private function getRedirectUri($provider)
    {
        $host = $_SERVER['HTTP_HOST'];
        $port = $_SERVER['PORT'];

        if ($port == '80') {
            $port = '';
        } else {
            $port = ':' . $port;
        }

        return 'http://' . $host . $port . '/login/' . $provider;
    }

    private function getConfig($provider)
    {
        $config = [];
        foreach ($_ENV as $key => $value) {
            if (stripos($key, $provider) === 0) {
                // GOOGLE_CLIENT_ID -> client_id
                $key = strtolower(substr($key, strlen($provider) + 1));
                // client_id -> Client Id
                $key = ucwords(str_replace('_', ' ', $key));
                // Client Id -> clientId
                $key = lcfirst(str_replace(' ', '', $key));
                if ($key === 'scopes') {
                    $value = explode(',', $value);
                }
                $config[$key] = $value;
            }
        }
        if (empty($config)) {
            throw new RuntimeException(sprintf(
                'Provider "%s" has not been configured',
                $provider
            ));
        }
        return $config;
    }

    public function index(Request $request, Response $response)
    {
        return $response->setContent($this->templates->render('index', [
            'providers' => $this->providers,
        ]));
    }

    public function login(Request $request, Response $response, array $args)
    {
        $redirect = $request->getUriForPath($request->getPathInfo());
        $provider = $this->getProvider($args['provider'], $redirect);

        $error = $request->query->get('error');
        $code  = $request->query->get('code');
        $state = $request->query->get('state');

        if ($error) {
            return $response->setContent($this->templates->render('error', [
                'error' => $error
            ]));
        }

        if (!$code) {
            $redirect = $provider->getAuthorizationUrl();
            $_SESSION['state'] = $provider->getState();
            return new RedirectResponse($redirect);
        }

        if ($state && (empty($_SESSION['state']) || $_SESSION['state'] !== $state)) {
            return $response->setContent($this->templates->render('error', [
                'error' => 'invalid_state'
            ]));
        }

        $token = $provider->getAccessToken('authorization_code', [
            'code' => $code,
        ]);

        $_SESSION['tokens'][$args['provider']] = $token;

        return new RedirectResponse($request->getUriForPath('/complete/' . $args['provider']));
    }

    public function complete(Request $request, Response $response, array $args)
    {
        $provider = $this->getProvider($args['provider']);

        $token = $_SESSION['tokens'][$args['provider']];
        $user  = $provider->getUserDetails($token);

        return $response->setContent($this->templates->render('user', [
            'provider' => $args['provider'],
            'id'       => $user->getUserId(),
            'details'  => $user->toArray(),
        ]));
    }
}
