<?php

namespace League\OAuth2\Client\Demo;

use ArrayObject;

class ProviderConfig extends ArrayObject
{
    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    public function providers()
    {
        return array_keys($this->getArrayCopy());
    }
}
