<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 2018/11/11
 * Time: 18:12
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Get Usable Coupons By User Sort By Hotel_id
$app->get('/api/coupons/customer/{username}', function (Request $request, Response $response, array $args) {
    $username = $args['username'];
    $current=date('Y-m-d');
    $sql = "SELECT C.id, C.username, C.type_id, C.hotel_id, C.expire_date, CT.value, CT.discount_type
            FROM coupon C, coupon_type CT
            WHERE username = :username AND C.type_id = CT.id AND C.expire_date >= :current
            AND C.id NOT IN ( SELECT coupon_id FROM payment)
            ORDER BY C.hotel_id";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':current', $current);
        $stmt->execute();
        $coupons = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        $response->write(json_encode($coupons));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error'=>$errorMessage]))->withStatus(500);
    }
});

//Get Usable Coupons By User and Hotel Sort By Expire Date
$app->get('/api/coupons/customer/{username}/hotel/{id}', function (Request $request, Response $response, array $args) {
    $username = $args['username'];
    $hotel_id = $args['id'];
    $current=date('Y-m-d');
    $sql = "SELECT C.id, C.username, C.type_id, C.hotel_id, C.expire_date, CT.value, CT.discount_type 
            FROM coupon C, coupon_type CT 
            WHERE username = :username AND hotel_id = :hotel_id AND C.type_id = CT.id AND C.expire_date >= :current
            AND C.id NOT IN ( SELECT coupon_id FROM payment)
            ORDER BY C.expire_date";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':hotel_id',$hotel_id);
        $stmt->bindParam(':current', $current);
        $stmt->execute();
        $coupons = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        $response->write(json_encode($coupons));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error'=>$errorMessage]))->withStatus(500);
    }
});

//Create Coupon By User and Hotel
$app->post('/api/coupons/create', function(Request $request, Response $response){
    $username = $request->getParam('username');
    $hotel_id = $request->getParam('hotel_id');
    $type_id = $request->getParam('type_id');
    $expire_date = $request->getParam('expire_date');
    $sql = "INSERT INTO coupon(username, type_id, hotel_id, expire_date) VALUES (:username, :type_id, :hotel_id, :expire_date)";
    // prepare query
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam('type_id',$type_id);
        $stmt->bindParam(':hotel_id',  $hotel_id);
        $stmt->bindParam(':expire_date', $expire_date);
        if($stmt->execute()){
            $result = $db->lastInsertId();
            $db = null;
            $responseArray = array();
            $responseArray["id"] = $result;
            return $response->write(json_encode($responseArray))->withStatus(200);
        }
        else{
            $db = null;
            return $response->write(json_encode(['error'=>'fail to create new tags']))->withStatus(500);
        }
    } catch(PDOException $e){
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error'=>$errorMessage]))->withStatus(500);
    }
});

//Create Coupons For All Customers By Hotel
$app->post('/api/coupons/create-all-for-hotel', function(Request $request, Response $response){
    $hotel_id = $request->getParam('hotel_id');
    $type_id = $request->getParam('type_id');
    $expire_date = $request->getParam('expire_date');

    $getUsers = "SELECT username FROM customers";
    $createCoupons = "INSERT INTO coupon(username, type_id, hotel_id, expire_date) VALUES (:username, $type_id, $hotel_id, :expire_date)";

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($getUsers);
        $customers = $stmt->fetchAll( PDO::FETCH_COLUMN);
        $db = null;
        $returnIds = array();

        try {
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();
            //iterate to create coupons one by one
            for ($x = 0; $x < sizeof($customers); $x++) {
                $stmt = $db->prepare($createCoupons);
                $username = $customers[$x];
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':expire_date', $expire_date);
                $stmt->execute();
                $result = $db->lastInsertId();
                array_push($returnIds,$result);
            }
            $db = null;
        } catch (PDOException $e) {
            $db = null;
            $errorMessage = $e->getMessage();
            return $response->write(json_encode(['error'=>''.$errorMessage]))->withStatus(500);
        }

        return $response->write(json_encode($returnIds))->withStatus(200);

    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error'=>''.$errorMessage]))->withStatus(500);
    }


});