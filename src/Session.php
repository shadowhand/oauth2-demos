<?php

namespace League\OAuth2\Client\Demo;

class Session
{
    public function has($key)
    {
        return !empty($_SESSION[$key]);
    }

    public function get($key, $default = null)
    {
        return $this->has($key) ? $_SESSION[$key] : $default;
    }

    public function set($key, $value)
    {
        return $_SESSION[$key] = $value;
    }

    public function merge($group, array $values)
    {
        $current = $this->get($group, []);
        $values  = array_replace_recursive($current, $values);
        return $_SESSION[$group] = $values;
    }
}
