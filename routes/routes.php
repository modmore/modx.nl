<?php

$app->get('/', function(\Slim\Http\Request $request, \Slim\Http\Response $response, array $args) {
    return (new \modxnl\Controllers\Home($this))
        ->returnTemplate('home.twig', $request, $response, $args);
})->setName('home');
