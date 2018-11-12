<?php
/**
 * Created by PhpStorm.
 * User: ao
 * Date: 2018-11-12
 * Time: 12:34 AM
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Get All room types for a hotel
$app->get('/api/hotels/{id}/room-types', function (Request $request, Response $response, array $args) {

    $hotel_id = $args['id'];

    $sql = "SELECT * FROM room_type rt " .
        "WHERE EXISTS " .
        "( select * from room r where rt.id = r.room_type_id and r.hotel_id = '$hotel_id')";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $roomTypes = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        return $response->write(json_encode($roomTypes));
    } catch (PDOException $e) {

        $db = null;

        $response->write($e);
        return $response->withStatus(500);
    }
});

// create a room type to a hotel, create all the rooms as well
$app->post('/api/hotels/{id}/room-types', function (Request $request, Response $response, array $args) {

    $parsedBody = $request->getParsedBody();
    $hotel_id = $args['id'];

    $db = new db();
    $db = $db->connect();

    $db->beginTransaction();
    // First create the room type
    try {
        $sql = "INSERT INTO room_type (`id`, `type_name`, `occupancy`, `description`, `price`, `total_slots`)" .
            " VALUES (NULL, :type_name, :occupancy, :description, :price, :slots);";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(":type_name", $parsedBody["type_name"]);
        $stmt->bindParam(":occupancy", $parsedBody["occupancy"]);
        $stmt->bindParam(":description", $parsedBody["description"]);
        $stmt->bindParam(":price", $parsedBody["price"]);
        $stmt->bindParam(":slots", $parsedBody["slots"]);

        if ($stmt->execute()) {
            $room_type_id = $db->lastInsertId();
        } else {
            return $response->write(['error'=>'could not create the room type'])->withStatus(500);
        }
    } catch (PDOException $e) {

        $db = null;

        $response->write($e);
        return $response->withStatus(500);
    }

    // create the room
    $stmt = $db->prepare("INSERT INTO room (`id`, `hotel_id`, `room_type_id`) VALUES (?,?,?)");
    try {
        for ($i = 0; $i < $parsedBody["slots"]; $i++)
        {
            if(!$stmt->execute([null, $hotel_id, $room_type_id])) {
                return $response->write(json_encode(['error'=>'could not create the room']))->withStatus(500);
            }
        }
        $db->commit();

        return $response->write(json_encode(['id'=>$room_type_id]));
    }catch (Exception $e){

        $db->rollback();

        return $response->write($e)->withStatus(500);
    }
});

// update a room type
$app->put('/api/room-types/{id}', function (Request $request, Response $response, array $args) {

    $id = $args['id'];

    $parsedBody = $request->getParsedBody();
    $updateQuery = "UPDATE room_type 
        SET 
            type_name = :type_name,
            occupancy = :occupancy,
            description = :description,
            price = :price,
            total_slots = :total_slots
         WHERE id = '$id' ";
    $db = new db();
    $db = $db->connect();

    try {

        $stmt = $db->prepare($updateQuery);
        $stmt->bindParam(":type_name", $parsedBody["type_name"]);
        $stmt->bindParam(":occupancy", $parsedBody["occupancy"]);
        $stmt->bindParam(":description", $parsedBody["description"]);
        $stmt->bindParam(":price", $parsedBody["price"]);
        $stmt->bindParam("total_slots", $parsedBody["total_slots"]);
        if ($stmt->execute()) {
            $db = null;
            return $response->write(json_encode(['id'=>$id]))->withStatus(200);
        } else {
            $db = null;
            $response->write(json_encode(['error'=>'Fail to update the room type']));
            return $response->withStatus(500);
        }
    } catch (PDOException $e) {

        $db = null;
        $response->write($e);
        return $response->withStatus(500);
    }
});

// delete a room type
$app->delete('/api/room-types/{id}', function (Request $request, Response $response, array $args) {

    $id = $args['id'];

    $sql = "DELETE FROM room_type WHERE id = '$id'";

    $db = new db();
    $db = $db->connect();
    try {
        $stmt = $db->query($sql);

        if ($stmt->execute()) {
            $db = null;
            return $response->withStatus(200);
        } else {
            $db = null;
            return $response->write(json_decode(['error'=>'no room type with given id found']))->withStatus(404);
        }
    } catch (PDOException $e) {
        $db->rollBack();
        $db = null;
        return $response->write($e)->withStatus(500);
    }
});