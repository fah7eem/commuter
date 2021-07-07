<?php
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../app/config.php';
require __DIR__ . '/../vendor/autoload.php';


$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
require __DIR__ . '/../app/startup.php';
$app->add($beforeMiddleware);
$app->run();