<?php

// Add simple monolog instance
// @todo configure this to send critical stuff via email and other stuff to a log
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('core');
    $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};