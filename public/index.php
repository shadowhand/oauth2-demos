<?php

// Find yourself
define('APP_ROOT', realpath(__DIR__ . '/..'));

// Load packages
require APP_ROOT . '/vendor/autoload.php';

// Inject configuration
Dotenv::load(APP_ROOT);

// Remember stuff
session_start();

// Boot the app
(new League\OAuth2\Client\Demo\Application)->run();
