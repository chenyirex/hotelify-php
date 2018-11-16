<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 2018/11/14
 * Time: 22:11
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Get All Cards by customers
$app->get('/api/cards/customer/{username}', function (Request $request, Response $response, array $args) {
    $username = $args['username'];
    $sql = "SELECT * FROM card WHERE username = :username";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $cards = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        $response->write(json_encode($cards));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error'=>''.$errorMessage]))->withStatus(500);
    }
});

//Add a card to a customer
$app->post('/api/cards/create', function(Request $request, Response $response){
    $card_number = $request->getParam('card_number');
    $card_holder_name = $request->getParam('card_holder_name');
    $username = $request->getParam('username');
    $expire_date = $request->getParam('expire_date');
    $csv = $request->getParam('csv');
    $sql = "INSERT INTO card(card_number, card_holder_name, username, csv, expire_date) VALUES (:card_number, :card_holder_name, :username, :csv, :expire_date)";
    // prepare query

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':card_number', $card_number);
        $stmt->bindParam(':card_holder_name',  $card_holder_name);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':expire_date',  $expire_date);
        $stmt->bindParam(':csv', $csv);
        if($stmt->execute()){
            $db = null;
            return $response->withStatus(200);
        }
        else{
            $db = null;
            return $response->write(json_encode(['error'=>'fail to create new card']))->withStatus(500);
        }
    } catch(PDOException $e){
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error'=>''.$errorMessage]))->withStatus(500);
    }
});

//Delete a card
$app->delete('/api/cards/delete/{number}', function (Request $request, Response $response, array $args) {
    $number = $args['number'];
    $sql = "DELETE FROM card WHERE card_number = :number";

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':number', $number);
        if ($stmt->execute()) {
            $db = null;
            return $response->withStatus(200);
        } else {
            $db = null;
            return $response->write(json_encode(['error' => 'Failed to delete card']))->withStatus(500);
        }
    }catch(PDOException $e){
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error'=>$errorMessage]))->withStatus(500);
    }
});