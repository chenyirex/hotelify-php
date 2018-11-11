<?php
/**
 * Created by PhpStorm.
 * User: ao
 * Date: 2018-11-10
 * Time: 11:43 PM
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

// Get All administrators
$app->get('/api/administrators', function(Request $request, Response $response){

    $sql = "SELECT * FROM administrator";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $administrators = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $response->write(json_encode($administrators));
        return $response->withStatus(200);
    } catch(PDOException $e){
        $response->write($e);
        return $response->withStatus(500);
    }
});

// Login for administrators
$app->post('/api/administrators/login', function(Request $request, Response $response) {

    $parsedBody = $request->getParsedBody();

    $username = $parsedBody['username'];
    $password = $request->getParam('password');

    $sql = "SELECT * FROM administrator where username = '$username'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);

        $administrator = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;

        if(!$administrator) {
            return $response->write('no admin found')->withStatus(404);
        }

        if($administrator->password == $password) {
            //SUCCESS
            $responseArray = array();
            $responseArray['userType'] = 'administrator';
            return $response->write(json_encode($responseArray));
        } else {
            //FAILED
            return $response->write('wrong credential')->withStatus(409);
        }
    } catch(PDOException $e){
        $response->write($e);
        return $response->withStatus(500);
    }
});