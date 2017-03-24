<?php

namespace modxnl\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class Html extends Base
{
    public $breadcrumbs = [];
    public function returnTemplate($template, Request $request, Response $response, array $args = array())
    {
        $this->setVariables($args);
        $this->render($template, $response);
        return $response;
    }

    public function setBreadcrumbs(array $crumbs)
    {
        $this->breadcrumbs = $crumbs;
        return $this;
    }

    public function getBreadcrumbs()
    {
        return $this->breadcrumbs;
    }
}