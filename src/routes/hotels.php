<?php
/**
 * Created by PhpStorm.
 * User: ao
 * Date: 2018-11-11
 * Time: 11:11 AM
 */
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
$app = new \Slim\App;
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res)->withHeader('Content-type', 'application/json');
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

// Get All hotels
$app->get('/api/hotels', function(Request $request, Response $response){

    $sql = "SELECT h.id as hotel_id, a.id as address_id, h.*, a.* FROM hotel h, address a where h.address_id = a.id";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $hotels = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $response->write(json_encode($hotels));
        return $response->withStatus(200);
    } catch(PDOException $e){
        $response->write($e);
        return $response->withStatus(500);
    }
});

// search hotels by branch name and brand name
$app->post('/api/hotels/search', function(Request $request, Response $response) {
});