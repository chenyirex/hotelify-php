<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 2018/11/12
 * Time: 17:19
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


// Get All tags by hotel
$app->get('/api/tags/hotel/{hotel_id}', function (Request $request, Response $response, array $args) {
    $hotel_id = $args['hotel_id'];
    $sql = "SELECT * FROM hotel_tag WHERE hotel_id = :hotel_id ORDER BY popularity DESC";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':hotel_id', $hotel_id);
        $stmt->execute();
        $tags = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        $response->write(json_encode($tags));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error'=>$errorMessage]))->withStatus(500);
    }
});

//Post a new tag
$app->post('/api/tags/create', function(Request $request, Response $response){
    $tag_name = $request->getParam('tag_name');
    $hotel_id = $request->getParam('hotel_id');
    $popularity = $request->getParam('popularity');
    $sql = "INSERT INTO hotel_tag(tag_name, hotel_id, popularity) VALUES (:tag_name, :hotel_id, :popularity)";
    // prepare query
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':tag_name', $tag_name);
        $stmt->bindParam(':hotel_id',  $hotel_id);
        $stmt->bindParam(':popularity', $popularity);
        if($stmt->execute()){
            $db = null;
            return $response->withStatus(200);
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

//Update a tag
$app->put('/api/tags/update', function (Request $request, Response $response) {
    $tag_name = $request->getParam('tag_name');
    $hotel_id = $request->getParam('hotel_id');
    $popularity = $request->getParam('popularity');
    $sql = "UPDATE hotel_tag 
         SET popularity = :popularity
         WHERE hotel_id = :hotel_id AND tag_name = :tag_name";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':tag_name', $tag_name);
        $stmt->bindParam(':hotel_id',  $hotel_id);
        $stmt->bindParam(':popularity', $popularity);
        $stmt->execute();
        $db = null;
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error'=>''.$errorMessage]))->withStatus(500);
    }
});