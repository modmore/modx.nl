<?php

// Add twig view helper for rendering stuff
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig($_ENV['TEMPLATES_DIR'], $_ENV['TWIG_ENVIRONMENT']);

    // Instantiate and add some extensions
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));
    $view->addExtension(new Twig_Extensions_Extension_Intl());
    $view->addExtension(new Twig_Extension_Debug());

    return $view;
};