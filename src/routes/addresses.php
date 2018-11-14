<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 2018/11/11
 * Time: 15:22
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Get address by id
$app->get('/api/addresses/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $sql = "SELECT * FROM address WHERE id = :id";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $address = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        $response->write(json_encode($address));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error'=>''.$errorMessage]))->withStatus(500);
    }
});

$app->post('/api/addresses/create', function(Request $request, Response $response){
    $country = $request->getParam('country');
    $province = $request->getParam('province');
    $city = $request->getParam('city');
    $street = $request->getParam('street');
    $postal_code = $request->getParam('postal_code');
    $sql = "INSERT INTO address(country, province, city, street, postal_code) VALUES (:country, :province, :city, :street, :postal_code)";
    // prepare query

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':province',  $province);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':street',  $street);
        $stmt->bindParam(':postal_code', $postal_code);
        $stmt->execute();

        $result = $db->lastInsertId();
        $db = null;
        $responseArray = array();
        $responseArray["id"] = $result;
        return $response->write(json_encode($responseArray))->withStatus(200);
    } catch(PDOException $e){
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error'=>''.$errorMessage]))->withStatus(500);
    }
});

$app->put('/api/addresses/update', function (Request $request, Response $response) {
    $country = $request->getParam('country');
    $province = $request->getParam('province');
    $city = $request->getParam('city');
    $street = $request->getParam('street');
    $postal_code = $request->getParam('postal_code');
    $id = $request->getParam('id');
    $sql = "UPDATE address 
         SET country = :country, province = :province, city = :city, street = :street, postal_code = :postal_code
         WHERE id = :id";

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':province',  $province);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':street',  $street);
        $stmt->bindParam(':postal_code', $postal_code);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $db = null;
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error'=>''.$errorMessage]))->withStatus(500);
    }
});
