<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;


$beforeMiddleware = function (Request $request, RequestHandler $handler) {
    $response = $handler->handle($request);
    return $response;
};

$container->set('templates', function () {
    $templates =  new \Slim\Views\PhpRenderer('../app/templates/');
    $templates->setLayout("layout.phtml");
    return $templates;
});

$app->get('/', function (Request $request, Response $response, $args) {
    $whereIsMyTransportStops = NEW WhereIsMyTransportStops();
    $stops = $whereIsMyTransportStops->data;
    return $this->get('templates')->render($response, "home.phtml", ["title" => "Home", 'stops' => $stops])->withStatus(200);
});

$app->get('/home', function (Request $request, Response $response, $args) {
    $whereIsMyTransportStops = NEW WhereIsMyTransportStops();
    $stops = $whereIsMyTransportStops->data;
    return $this->get('templates')->render($response, "home.phtml", ["title" => "Home", 'stops' => $stops])->withStatus(200);
});