<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Get All Customers
$app->get('/api/customers', function (Request $request, Response $response) {

    $sql = "SELECT * FROM customer";
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
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => '' . $errorMessage]))->withStatus(500);
    }
});

// Get Customer By Username
$app->get('/api/customers/{username}', function (Request $request, Response $response, array $args) {
    $username = $args['username'];
    $sql = "SELECT * FROM customer WHERE username = :username";

    try {
        // Get DB Object
        $db = new db();

        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $customer = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        if (empty($customer)) {
            return $response->write(json_encode(['error' => 'No user found for ' . $username]))->withStatus(404);
        }
        $response->write(json_encode($customer));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => '' . $errorMessage]))->withStatus(500);
    }
});

// Login for customers
$app->post('/api/customers/login', function (Request $request, Response $response) {

    $username = $request->getParam('username');
    $password = $request->getParam('password');

    $sql = "SELECT * FROM customer where username = '$username'";

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);

        $customer = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;

        if (!$customer) {
            return $response->write(json_encode(['error' => 'No customer found']))->withStatus(404);
        }

        if ($customer->password == $password) {
            //SUCCESS
            $responseArray = array();
            $responseArray['userType'] = 'customer';
            return $response->write(json_encode($responseArray));
            return $response->withStatus(200);
        } else {
            return $response->write(json_encode(['error' => 'Wrong credential']))->withStatus(401);
        }
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => '' . $errorMessage]))->withStatus(500);
    }
});

$app->post('/api/customers/signup', function (Request $request, Response $response) {
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $username = $request->getParam('username');
    $password = $request->getParam('password');
    $email = $request->getParam('email');
    $phone_number = $request->getParam('phone_number');
    $sql = "INSERT INTO customer(first_name, last_name, username, password, email, phone_number, points) VALUES (:first_name, :last_name, :username, :password, :email, :phone_number, 0)";
    // prepare query

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone_number', $phone_number);
        if ($stmt->execute()) {
            $db = null;
            $responseArray = array();
            $responseArray["id"] = $username;
            return $response->write(json_encode($responseArray))->withStatus(200);
        } else {
            $db = null;
            return $response->write(json_encode(['error' => 'fail to create new customer']))->withStatus(500);
        }
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => '' . $errorMessage]))->withStatus(500);
    }
});
//update by username
$app->put('/api/customers/update', function (Request $request, Response $response) {
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $username = $request->getParam('username');
    $password = $request->getParam('password');
    $email = $request->getParam('email');
    $phone_number = $request->getParam('phone_number');
    $address_id = $request->getParam('address_id');
    $points = $request->getParam('points');
    $sql = "UPDATE customer
         SET first_name = :first_name, last_name = :last_name,  password = :password, phone_number = :phone_number,
              email = :email, address_id = :address_id, points = :points
         WHERE username = :username";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':points', $points);
        $stmt->bindParam(':address_id', $address_id);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $db = null;
        $responseArray = array();
        $responseArray["id"] = $username;
        return $response->write(json_encode($responseArray))->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => '' . $errorMessage]))->withStatus(500);
    }
});