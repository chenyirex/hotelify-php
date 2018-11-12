<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Get All Customers
$app->get('/api/customers', function (Request $request, Response $response) {

    $sql = "SELECT * FROM customers";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        $response->write(json_encode($customers));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $response->write($e);
        return $response->withStatus(500);
    }
});

// Get Customer By Username
$app->get('/api/customers/{username}', function (Request $request, Response $response, array $args) {
    $username = $args['username'];
    $sql = "SELECT * FROM customers WHERE username = :username";

    try {
        // Get DB Object
        $db = new db();

        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $customer = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;

        $response->write(json_encode($customer));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $response->write($e);
        return $response->withStatus(500);
    }
});

// Login for customers
$app->post('/api/customers/login', function (Request $request, Response $response) {

    $username = $request->getParam('username');
    $password = $request->getParam('password');

    $sql = "SELECT * FROM customers where username = '$username'";

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);

        $customer = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;

        if (!$customer) {
            return $response->write('no admin found')->withStatus(404);
        }

        if ($customer->password == $password) {
            //SUCCESS
            $responseArray = array();
            $responseArray['userType'] = 'customer';
            return $response->write(json_encode($responseArray));
            return $response->withStatus(200);
        } else {
            //FAILED
            return $response->write('wrong credential')->withStatus(409);
        }
    } catch (PDOException $e) {
        $db->rollBack();
        $db = null;
        $response->write($e);
        return $response->withStatus(500);
    }
});

$app->post('/api/customers/signup', function(Request $request, Response $response){
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $username = $request->getParam('username');
    $password = $request->getParam('password');
    $email = $request->getParam('email');
    $phone = $request->getParam('phone');
    $sql = "INSERT INTO customers(first_name, last_name, username, password, email, phone, points) VALUES (:first_name, :last_name, :username, :password, :email, :phone, 0)";
    // prepare query

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name',  $last_name);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password',  $password);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone',  $phone);
        $stmt->execute();
        $db = null;
        $response->write('{"notice": {"text": "Customers Added"}}');
        return $response->withStatus(200);
    } catch(PDOException $e){
        $db = null;
        $response->write('{"error": {"text": '.$e->getMessage().'}}');
        return $response->withStatus(500);
    }
});
//update by username
$app->put('/api/customers/update', function (Request $request, Response $response) {
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $username = $request->getParam('username');
    $password = $request->getParam('password');
    $email = $request->getParam('email');
    $phone = $request->getParam('phone');
    $address_id = $request->getParam('address_id');
    $points = $request->getParam('points');
    $sql = "UPDATE customers
         SET first_name = :first_name, last_name = :last_name,  password = :password, phone = :phone,
              email = :email, address_id = :address_id, points = :points
         WHERE username = :username";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name',  $last_name);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':phone',  $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':points', $points);
        $stmt->bindParam(':address_id', $address_id);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $db = null;
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db->rollBack();
        $db = null;
        $response->write('{"error": {"text": "failed update on Hotel"}}');
        return $response->withStatus(500);
    }
});