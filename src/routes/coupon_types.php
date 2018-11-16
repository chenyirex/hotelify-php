<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 2018/11/11
 * Time: 17:58
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Get All Coupon types
$app->get('/api/coupon-types', function (Request $request, Response $response) {

    $sql = "SELECT * FROM coupon_type";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $types = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        $response->write(json_encode($types));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error'=>$errorMessage]))->withStatus(500);
    }
});

//add coupon types
$app->post('/api/coupon-types/create', function(Request $request, Response $response){
    $discount_type = $request->getParam('discount_type');
    $value = $request->getParam('value');
    $sql = "INSERT INTO coupon_type(discount_type, value) VALUES (:discount_type, :value)";
    // prepare query
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':discount_type', $discount_type);
        $stmt->bindParam(':value',  $value);
        if($stmt->execute()){
            $id = $db->lastInsertId();
            $db = null;
            $responseArray = array();
            $responseArray["id"] = $id;
            return $response->write(json_encode($responseArray))->withStatus(200);
        }
        else{
            $db = null;
            return $response->write(json_encode(['error'=>'fail to create new coupon type']))->withStatus(500);
        }
    } catch(PDOException $e){
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error'=>''.$errorMessage]))->withStatus(500);
    }
});

//Delete a coupon type
$app->delete('/api/coupon-types/delete/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $sql = "DELETE FROM coupon_type WHERE id = :id";

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            $db = null;
            return $response->withStatus(200);
        } else {
            $db = null;
            return $response->write(json_encode(['error' => 'Failed to delete coupon type']))->withStatus(500);
        }
    }catch(PDOException $e){
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error'=>$errorMessage]))->withStatus(500);
    }
});