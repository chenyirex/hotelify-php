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

    $sql = "SELECT * FROM room_type where hotel_id = '$hotel_id' ";
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

// create a room type to a hotel
$app->post('/api/hotels/{id}/room-types', function (Request $request, Response $response, array $args) {

    $hotel_id = $args['id'];

    $sql = "INSERT INTO room_type (`id`, `type_name`, `occupancy`, `description`, `price`, `available_slots`, `hotel_id`, `total_slots`)" .
        " VALUES (NULL, :type_name, :occupancy, :description, :price, :slots, :hotel_id, :slots);";

    $parsedBody = $request->getParsedBody();

    $db = new db();
    $db = $db->connect();

    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":type_name", $parsedBody["type_name"]);
        $stmt->bindParam(":occupancy", $parsedBody["occupancy"]);
        $stmt->bindParam(":description", $parsedBody["description"]);
        $stmt->bindParam(":price", $parsedBody["price"]);
        $stmt->bindParam(":slots", $parsedBody["slots"]);
        $stmt->bindParam(":hotel_id", $hotel_id);

        if ($stmt->execute()) {
            $insertedId = $db->lastInsertId();

            $db = null;

            $responseArray = array();
            $responseArray["id"] = $insertedId;
            return $response->write(json_encode($responseArray))->withStatus(200);
        }
    } catch (PDOException $e) {

        $db = null;

        $response->write($e);
        return $response->withStatus(500);
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
            available_slots = :available_slots,
            hotel_id = :hotel_id,
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
        $stmt->bindParam(":available_slots", $parsedBody["available_slots"]);
        $stmt->bindParam(":hotel_id", $parsedBody["hotel_id"]);
        $stmt->bindParam("total_slots", $parsedBody["total_slots"]);
        if ($stmt->execute()) {
            $db = null;
            return $response->withStatus(200);
        } else {
            $db = null;
            $response->write('{"error": {"text": "failed update the room-type"}}');
            return $response->withStatus(500);
        }
    } catch (PDOException $e) {
        $db->rollBack();
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
            return $response->write('{"error": {"text": "No hotel found for id = ' . $id . '"}}')->withStatus(404);
        }
    } catch (PDOException $e) {
        $db->rollBack();
        $db = null;
        return $response->write($e)->withStatus(500);
    }
});