<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

$container->set('templates', function () {
    $templates =  new \Slim\Views\PhpRenderer('../app/templates/');
    $templates->setLayout("layout.phtml");
    return $templates;
});

$app->get('/', function (Request $request, Response $response) {
    $whereIsMyTransportStops = NEW WhereIsMyTransportStops();
    $stops = $whereIsMyTransportStops->data;
    return $this->get('templates')->render($response, $stops == false ? "unavailable.phtml" : "home.phtml", ["title" => "Commuter", 'stops' => $stops])->withStatus(200);
});

$app->get('/home', function (Request $request, Response $response) {
    $whereIsMyTransportStops = NEW WhereIsMyTransportStops();
    $stops = $whereIsMyTransportStops->data;
    return $this->get('templates')->render($response, $stops == false ? "unavailable.phtml" : "home.phtml", ["title" => "Commuter", 'stops' => $stops])->withStatus(200);
});

$app->get('/journey/{start}/{destination}', function (Request $request, Response $response, $args) {
    $whereIsMyTransportJourneys = NEW WhereIsMyTransportJourneys($args['start'], $args['destination']);
    $journeys = $whereIsMyTransportJourneys->data;
    $response->getBody()->write(json_encode($journeys));
    return $response->withAddedHeader('Content-Type','application/json')->withStatus($journeys == false ? 500 : 200);
});