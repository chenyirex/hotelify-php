<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 2018/11/11
 * Time: 16:44
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Get All reviews by hotel
$app->get('/api/reviews/hotel/{hotel_id}', function (Request $request, Response $response, array $args) {
    $hotel_id = $args['hotel_id'];
    $sql = "SELECT * FROM review WHERE hotel_id = :hotel_id";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':hotel_id', $hotel_id);
        $stmt->execute();
        $reviews = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        $response->write(json_encode($reviews));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error'=>$errorMessage]))->withStatus(500);
    }
});

// Get All reviews by customer
$app->get('/api/reviews/username/{username}', function (Request $request, Response $response, array $args) {
    $username = $args['username'];
    $sql = "SELECT * FROM review WHERE username = :username";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $reviews = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        $response->write(json_encode($reviews));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error'=>$errorMessage]))->withStatus(500);
    }
});

//Post a new review
$app->post('/api/reviews/create', function(Request $request, Response $response){
    $username = $request->getParam('username');
    $hotel_id = $request->getParam('hotel_id');
    $rating = $request->getParam('rating');
    $comment = $request->getParam('comment');
    $sql = "INSERT INTO review(username, hotel_id, rating, comment) VALUES (:username, :hotel_id, :rating, :comment)";
    // prepare query
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':hotel_id',  $hotel_id);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment',  $comment);
        if($stmt->execute()){
            $responseArray = array();
            $id = $db->lastInsertId();
            $db = null;
            $responseArray["id"] = $id;
            return $response->write(json_encode($responseArray))->withStatus(200);
        }
        else{
            $db = null;
            return $response->write(json_encode(['error'=>'fail to create new reviews']))->withStatus(500);
        }
    } catch(PDOException $e){
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error'=>$errorMessage]))->withStatus(500);
    }
});