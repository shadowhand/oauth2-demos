<?php

namespace League\OAuth2\Client\Demo;

use Spark\Responder\PlatesResponder;

class OAuthResponder extends PlatesResponder
{
    protected function processing()
    {
        // Redirect to the provider authorization URL
        $this->response = $this->response->withStatus(302);
        $this->response = $this->response->withHeader('Location', $this->payload->getOutput());
    }

    protected function authenticated()
    {
        // Redirect to the user page for this provider
        $data = $this->payload->getOutput();
        $uri  = '/user/' . $data['provider'];

        $this->response = $this->response->withStatus(302);
        $this->response = $this->response->withHeader('Location', $uri);
    }

    protected function notAuthenticated()
    {
        $this->response = $this->response->withStatus(400);
        $this->responseBody([
            'input' => $this->payload->getInput(),
            'messages' => $this->payload->getMessages(),
        ]);
    }
}
