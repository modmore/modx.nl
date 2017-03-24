<?php
$_ENV['SLIM_MODE'] = 'development';

$_ENV['PROJECT_DIR'] = __DIR__ . '/';
$_ENV['CACHE_DIR'] = $_ENV['PROJECT_DIR'] . 'cache/';
$_ENV['TEMPLATES_DIR'] = $_ENV['PROJECT_DIR'] . 'templates';
$_ENV['MODEL_DIR'] = $_ENV['PROJECT_DIR'] . 'model/';
$_ENV['VIEWS_DIR'] = $_ENV['PROJECT_DIR'] . 'views/';
$_ENV['CONTROLLERS_DIR'] = $_ENV['PROJECT_DIR'] . 'controllers/';

$_ENV['TWIG_ENVIRONMENT'] = [
    'cache' => $_ENV['CACHE_DIR'] . 'twig/',
    'auto_reload' => true,
    'strict_variables' => true,
];

$_ENV['SESSION_NAME'] = 'MODXNL';

$_ENV['SLIM_APP_OPTIONS'] = [
    'settings' => [
        'displayErrorDetails' => true,
    ]
];