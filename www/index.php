<?php
define('APP_ROOT_DIR', dirname(__DIR__) . '/');
define('APP_CONFIG_DIR', dirname(__DIR__) . '/config/');

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/environment.php';

// Create Dependency Injection Container
$container = new Slim\Container($_ENV['SLIM_APP_OPTIONS']);

require APP_ROOT_DIR . 'container/logger.php';
//require APP_ROOT_DIR . 'container/filesystems.php';
require APP_ROOT_DIR . 'container/view.php';

// Add error handler
$container['errorHandler'] = function ($container) {
    return new \modxnl\Handlers\Error($container);
};

// Create the Slim app
$app = new Slim\App($container);

//require dirname(__DIR__) . '/middleware/middleware.php';

require dirname(__DIR__) . '/routes/routes.php';

// Run the app
$app->run();
