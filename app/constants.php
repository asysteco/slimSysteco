<?php

define('BASE_PATH', __DIR__ . '/..');
define('ENV_FILE', BASE_PATH . '/env/env.json');
if (is_file(ENV_FILE) && is_readable(ENV_FILE)) {
    $_ENV = (array)json_decode(file_get_contents(ENV_FILE));
}

define('APP_HOST', $_SERVER['HTTP_HOST']);
define('ENVIRONMENT', $_ENV['ENVIRONMENT']);

// ENVIRONMENTS
define('PRODUCTION', 'production');
define('TESTING', 'testing');
define('DEVELOPMENT', 'development');