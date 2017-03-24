<?php

namespace modxnl\Handlers;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Error extends \Slim\Handlers\Error {
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->displayErrorDetails = $container->get('settings')['displayErrorDetails'];
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, \Exception $exception)
    {
        $this->container->get('logger')->alert($exception);
        return parent::__invoke($request, $response, $exception);
    }
}