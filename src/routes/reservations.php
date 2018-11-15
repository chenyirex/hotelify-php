<?php
/**
 * Created by PhpStorm.
 * User: chenyi
 * Date: 2018-11-12
 * Time: 4:58 PM
 */

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * get all reservations for a customer
 */
$app->get('/api/reservations/customer/{username}', function (Request $request, Response $response, array $args) {

    $username = $args['username'];

    $getQuery = "SELECT re.id, re.username, re.payment_id, rm.room_id, r.hotel_id, r.room_type_id, rm.checkin_date, rm.checkout_date 
                FROM reservation re, reservation_room rm, room r  
                WHERE re.username = :username AND re.id = rm.reservation_id AND r.id = rm.room_id";
    
    $db = new db();
    $db = $db->connect();

    try {
        $stmt = $db->prepare($getQuery);

        $stmt->bindParam("username", $username);

        $stmt->execute();

        $reservations = $stmt->fetchAll(PDO::FETCH_OBJ);

        $db = null;

        return $response->write(json_encode($reservations))->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => '' . $errorMessage]))->withStatus(500);
    }
});

/**
 * Example POST body
 *     {
 *       "id": "4",
 *       "username": "rex",
 *       "payment_id": null,
 *       "room_id": "29",
 *       "checkin_date": "2018-11-11",
 *       "checkout_date": "2018-12-12"
 *      }
 * id is not required, if passed in, it will create reservation-room relation on reservation 4.
 * This would be used when we have multiple rooms reserved under one reservation.
 * A normal behaviour would be passing in the first request without id, then append the id received from response for the
 * subsequent requests.
 * if id not passed in, it will create a new reservation every time.
 */
$app->post('/api/reservations/create', function (Request $request, Response $response) {

    $parsedBody = $request->getParsedBody();

    $id = array_key_exists('id', $parsedBody) ? $parsedBody['id'] : null;;

    $reservationQuery = "INSERT INTO reservation (username) VALUES (:username)";
    $reservationRoomQuery = "INSERT INTO reservation_room (reservation_id, checkin_date,checkout_date,room_id) 
                            VALUES (:reservation_id,:checkin_date,:checkout_date,:room_id)";

    $db = new db();
    $db = $db->connect();

    try {
        $db->beginTransaction();

        if (!$id) {
            $stmt = $db->prepare($reservationQuery);
            $stmt->bindParam("username", $parsedBody['username']);
            if ($stmt->execute()) {
                $id = $db->lastInsertId();
            } else {
                $db = null;
                $response->write('{"error": {"text": "failed create on reservation"}}');
                return $response->withStatus(500);
            }
        }

        $stmt = $db->prepare($reservationRoomQuery);

        $stmt->bindParam("reservation_id", $id);
        $stmt->bindParam("checkin_date", $parsedBody['checkin_date']);
        $stmt->bindParam("checkout_date", $parsedBody['checkout_date']);
        $stmt->bindParam("room_id", $parsedBody['room_id']);

        if ($stmt->execute()) {
            $db->commit();
            $db = null;

            $responseArray = array();
            $responseArray["id"] = $id;

            $response->write(json_encode($responseArray));
            return $response->withStatus(200);
        } else {
            $db = null;
            $response->write('{"error": {"text": "failed create on reservation_room"}}');
            return $response->withStatus(500);
        }
    } catch (PDOException $e) {
        $db->rollBack();
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => '' . $errorMessage]))->withStatus(500);
    }
});
/**
 * update a reservation, you can only update checkin_date, checkout_date.
 */
$app->put('/api/reservations/update/{id}', function (Request $request, Response $response, array $args) {

    $parsedBody = $request->getParsedBody();

    $updateQuery = "UPDATE reservation_room SET checkin_date = :checkin_date, checkout_date = :checkout_date 
                    WHERE reservation_id = :id";

    $db = new db();
    $db = $db->connect();

    try {
        $stmt = $db->prepare($updateQuery);

        $stmt->bindParam("id", $args['id']);
        $stmt->bindParam("checkin_date", $parsedBody['checkin_date']);
        $stmt->bindParam("checkout_date", $parsedBody['checkout_date']);

        if ($stmt->execute()) {
            $db = null;
            return $response->withStatus(200);
        } else {
            $db = null;
            return $response->write(json_encode(['error' => 'No such reservation']))->withStatus(500);
        }
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => '' . $errorMessage]))->withStatus(500);
    }
});

/**
 * delete a reservation
 */
$app->delete('/api/reservations/delete/{id}', function (Request $request, Response $response, array $args) {

    $deleteQuery = "DELETE FROM reservation WHERE id = :id ";

    $db = new db();
    $db = $db->connect();

    try {
        $stmt = $db->prepare($deleteQuery);
        $stmt->bindParam('id', $args['id']);

        $stmt->execute();

        $db = null;
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => '' . $errorMessage]))->withStatus(500);
    }
});