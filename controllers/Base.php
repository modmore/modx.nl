<?php

namespace modxnl\Controllers;

use DMS\Service\Meetup\MeetupKeyAuthClient;
use Monolog\Logger;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;
use Slim\Views\Twig;

class Base
{
    /** @var Logger */
    protected $logger;
    /** @var Router */
    protected $router;
    /** @var Container */
    protected $container;
    /** @var Request */
    protected $request;
    /** @var Response */
    protected $response;

    protected $arguments = array();
    protected $variables = array();

    protected $options = array();
    /** @var Twig */
    protected $view;

    public function __construct($container, array $options = array())
    {
        $this->container = $container;
        $this->options = $options;
        $this->logger = $this->container->get('logger');
        $this->view = $this->container->get('view');
        $this->router = $this->container->get('router');
    }

    public function get(Request $request, Response $response, array $args = array()) {
        // empty
    }


    public function initialize(Request $request, Response $response, array $args = array())
    {
        $this->request =& $request;
        $this->response =& $response;
        $this->setArguments($args);
        $this->setVariable('args', $args);

        $this->getMeetupEvents();

        return true;
    }

    public function render($template, $response = null)
    {
        if (!$response && $this->response) {
            $response = $this->response;
        }

        $this->container->view->render($response, $template, $this->getVariables());
    }

    public function setVariable($key, $value)
    {
        $this->variables[$key] = $value;
    }

    public function setVariables(array $values = array(), $prefix = '')
    {
        if (!empty($prefix)) {
            $this->setVariable($prefix, $values);
        }
        else {
            foreach ($values as $key => $value) {
                $this->setVariable($key, $value);
            }
        }
    }

    public function getVariable($key, $default = null)
    {
        if (isset($this->variables[$key])) {
            return $this->variables[$key];
        }
        return $default;
    }

    public function getVariables()
    {
        return $this->variables;
    }

    protected function setArguments(array $args = array())
    {
        $this->arguments = array_merge($this->arguments, $args);
    }


    /**
     * @return mixed
     */
    public function getArgument($key, $default = null)
    {
        if (array_key_exists($key, $this->arguments)) {
            return $this->arguments[$key];
        }
        return $default;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    public function getMeetupEvents()
    {
        $client = MeetupKeyAuthClient::factory(array('key' => $_ENV['MEETUP_KEY']));

        // Get future events
        $response = $client->getEvents([
            'group_urlname' => $_ENV['MEETUP_GROUP_PATH'],
            'status' => 'upcoming'
        ]);
        $future = [];
        foreach ($response as $event) {
            $e = $event;
            $e['time'] = $e['time'] / 1000;
            $future[] = $e;
        }
        $this->setVariable('future_events', $future);

        // Past events
        $response = $client->getEvents([
            'group_urlname' => $_ENV['MEETUP_GROUP_PATH'],
            'status' => 'past',
            'desc' => 'desc',
        ]);
        $past = [];
        foreach ($response as $event) {
            $e = $event;
            $e['time'] = $e['time'] / 1000;
            $past[] = $e;
        }
        $this->setVariable('past_events', $past);
    }
}