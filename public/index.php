<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require '../vendor/autoload.php';
require '../src/config/db.php';

$app = new \Slim\App;
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Content-type', 'application/json')
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

require '../src/routes/customers.php';
require '../src/routes/administrators.php';
require '../src/routes/hotels.php';
require '../src/routes/coupon_types.php';
require '../src/routes/coupon.php';
require '../src/routes/hotel_tag.php';
require '../src/routes/reviews.php';
require '../src/routes/addresses.php';
require '../src/routes/roomTypes.php';
require '../src/routes/reservations.php';
require '../src/routes/cards.php';
require '../src/routes/payments.php';
require '../src/routes/stats.php';

$app->run();