<?php
/**
 * Created by PhpStorm.
 * User: chenyi
 * Date: 2018-11-16
 * Time: 1:54 PM
 */

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Total number of users
 *
 * response:{
 * "result": "3"
 * }
 *
 */
$app->get('/api/stats/user-count', function (Request $request, Response $response) {

    $query = "SELECT COUNT(*) AS result FROM customer";

    $db = new db();
    $db = $db->connect();

    try {

        $stmt = $db->query($query);

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        $response->write(json_encode($result));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => $errorMessage]))->withStatus(500);
    }
});
/**
 * Total number of hotels
 *
 * response:{
 * "result": "6"
 * }
 */
$app->get('/api/stats/hotel-count', function (Request $request, Response $response) {

    $query = "SELECT COUNT(*) AS result FROM hotel";

    $db = new db();
    $db = $db->connect();

    try {

        $stmt = $db->query($query);

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        $response->write(json_encode($result));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => $errorMessage]))->withStatus(500);
    }
});
/**
 * Total number of reservations that hotelify has received
 *
 * response:{
 * "result": "5"
 * }
 */
$app->get('/api/stats/reservation-count', function (Request $request, Response $response) {

    $query = "SELECT COUNT(*) AS result FROM reservation";

    $db = new db();
    $db = $db->connect();

    try {

        $stmt = $db->query($query);

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        $response->write(json_encode($result));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => $errorMessage]))->withStatus(500);
    }
});
/**
 * Provides number of rooms for all hotels
 * response: [
 * {
 * "id": "1",
 * "brand_name": "Walter Gage",
 * "branch_name": "UBC",
 * "property_class": "3",
 * "address_id": "3",
 * "description": "Walter Gage is known for its positive energy and superb location. Three high-rise towers are conveniently located near the The Nest, bus loop, and many campus recreational facilities.",
 * "overall_rating": "good",
 * "phone_number": "6048221020",
 * "result": "16"
 * },
 * {
 * "id": "2",
 * "brand_name": "Fairmont",
 * "branch_name": "Vancouver Airport",
 * "property_class": "4",
 * "address_id": "4",
 * "description": "Set within the Vancouver International Airport, this upscale hotel is 1 km from YVR-Airport Station train station and 9 km from VanDusen Botanical Garden.",
 * "overall_rating": null,
 * "phone_number": "8008208820",
 * "result": "6"
 * },
 * {
 * "id": "4",
 * "brand_name": "Exchange Hotel",
 * "branch_name": "Vancouver",
 * "property_class": "4",
 * "address_id": "6",
 * "description": "Situated within 200 metres of Waterfront Centre Mall Vancouver and Vancouver Lookout at Harbour Centre, EXchange Hotel Vancouver features rooms with air conditioning. Free WiFi is available.",
 * "overall_rating": null,
 * "phone_number": "6047190900",
 * "result": "2"
 * },
 * {
 * "id": "5",
 * "brand_name": "Four Seasons",
 * "branch_name": "Vancouver",
 * "property_class": "5",
 * "address_id": "1",
 * "description": "Modern rooms & plush suites with skyline views, plus an indoor/outdoor pool & a seafood restaurant.",
 * "overall_rating": null,
 * "phone_number": "6046899333",
 * "result": "4"
 * },
 * {
 * "id": "6",
 * "brand_name": "Chelsea Hotel",
 * "branch_name": "Toronto",
 * "property_class": "3",
 * "address_id": "7",
 * "description": null,
 * "overall_rating": null,
 * "phone_number": null,
 * "result": "12"
 * },
 * {
 * "id": "7",
 * "brand_name": "Fairmont",
 * "branch_name": "Vancouver Downtown",
 * "property_class": "4",
 * "address_id": "8",
 * "description": "A 2-minute walk from the Vancouver Art Gallery and a 4-minute walk from Vancouver City Centre Station, this elegant hotel dating from 1939 is a 10-minute walk from the Canada Place convention centre.",
 * "overall_rating": null,
 * "phone_number": "6046843131",
 * "result": "5"
 * }
 * ]
 */
$app->get('/api/stats/hotel-room-count', function (Request $request, Response $response) {

    $query = "SELECT  h.*, COUNT(*) AS result FROM room r, hotel h WHERE r.hotel_id = h.id GROUP BY r.hotel_id ";

    $db = new db();
    $db = $db->connect();

    try {

        $stmt = $db->query($query);

        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        $response->write(json_encode($result));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => $errorMessage]))->withStatus(500);
    }
});
/**
 * Total revenue that hotelify brings to all hotels
 * response: {
 * "result": "850"
 * }
 */
$app->get('/api/stats/revenue', function (Request $request, Response $response) {

    $query = "SELECT SUM(payment.amount) AS result FROM payment";

    $db = new db();
    $db = $db->connect();

    try {

        $stmt = $db->query($query);

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        $response->write(json_encode($result));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => $errorMessage]))->withStatus(500);
    }
});
/**
 * Total revenue that hotelify brings to a hotel
 * response: {
 * "result": "850"
 * }
 */
$app->get('/api/stats/hotel/{hotel_id}/revenue', function (Request $request, Response $response, array $args) {

    $hotel_id = $args['hotel_id'];

    $query = "  SELECT
                    SUM(p.amount) AS result
                FROM
                    (
                    SELECT
                        reservation_id
                    FROM
                        reservation_hotel
                    WHERE
                        hotel_id = :hotel_id
                    GROUP BY
                        reservation_id,
                        hotel_id
                ) AS rid,
                reservation r,
                payment p
                WHERE
                    rid.reservation_id = r.id AND r.payment_id IS NOT NULL AND p.id = r.payment_id";

    $db = new db();
    $db = $db->connect();
    try {
        $stmt = $db->prepare($query);
        $stmt->bindParam('hotel_id', $hotel_id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_OBJ);

        $db = null;

        $response->write(json_encode($result));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => $errorMessage]))->withStatus(500);
    }
});
/**
 * response:{
 * "numOfRoomsReserved": "3"
 * }
 *
 */
$app->get('/api/stats/max-room-reserved', function (Request $request, Response $response) {

    $query = "SELECT MAX(numberOfRooms) AS numOfRoomsReserved FROM rooms_per_reservation";

    $db = new db();
    $db = $db->connect();

    try {

        $stmt = $db->query($query);

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        $response->write(json_encode($result));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => $errorMessage]))->withStatus(500);
    }
});

/**
 * response: {
 * "numOfRoomsReserved": "1"
 * }
 */
$app->get('/api/stats/min-room-reserved', function (Request $request, Response $response) {

    $query = "SELECT MIN(numberOfRooms) AS numOfRoomsReserved FROM rooms_per_reservation";

    $db = new db();
    $db = $db->connect();

    try {

        $stmt = $db->query($query);

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        $response->write(json_encode($result));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => $errorMessage]))->withStatus(500);
    }
});

/**
 * response:{
 * "hotel_id": "1",
 * "tag_name": "clean",
 * "popularity": "7"
 * }
 */
$app->get('/api/stats/most-popular-tag', function (Request $request, Response $response) {

    $query = " SELECT * from hotel_tag where popularity = (SELECT MAX(popularity) FROM hotel_tag)";

    $db = new db();
    $db = $db->connect();

    try {

        $stmt = $db->query($query);

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        $response->write(json_encode($result));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => $errorMessage]))->withStatus(500);
    }
});

/**
 * response:[
 * {
 * "hotel_id": "1",
 * "result": "4",
 * "id": "1",
 * "brand_name": "Walter Gage",
 * "branch_name": "UBC",
 * "property_class": "3",
 * "address_id": "3",
 * "description": "Walter Gage is known for its positive energy and superb location. Three high-rise towers are conveniently located near the The Nest, bus loop, and many campus recreational facilities.",
 * "overall_rating": "good",
 * "phone_number": "6048221020"
 * },
 * {
 * "hotel_id": "6",
 * "result": "1",
 * "id": "6",
 * "brand_name": "Chelsea Hotel",
 * "branch_name": "Toronto",
 * "property_class": "3",
 * "address_id": "7",
 * "description": null,
 * "overall_rating": null,
 * "phone_number": null
 * }
 * ]
 */
$app->get('/api/stats/reservations-per-hotel', function (Request $request, Response $response) {

    $query = "SELECT * FROM reservations_per_hotel rph, hotel h WHERE rph.hotel_id = h.id";

    $db = new db();
    $db = $db->connect();

    try {

        $stmt = $db->query($query);

        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        $response->write(json_encode($result));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => $errorMessage]))->withStatus(500);
    }
});

/**
 * response: {
 * "hotel_id": "1",
 * "result": "4",
 * "id": "1",
 * "brand_name": "Walter Gage",
 * "branch_name": "UBC",
 * "property_class": "3",
 * "address_id": "3",
 * "description": "Walter Gage is known for its positive energy and superb location. Three high-rise towers are conveniently located near the The Nest, bus loop, and many campus recreational facilities.",
 * "overall_rating": "good",
 * "phone_number": "6048221020"
 * }
 * result is the number of reservations per hotel
 */
$app->get('/api/stats/most-popular-hotel', function (Request $request, Response $response) {

    $query = "  SELECT
                    *
                FROM
                    reservations_per_hotel rph,
                    hotel h
                WHERE
                    rph.hotel_id = h.id AND rph.result =(
                    SELECT
                        MAX(result)
                    FROM
                        `reservations_per_hotel`
                );";

    $db = new db();
    $db = $db->connect();

    try {

        $stmt = $db->query($query);

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        $response->write(json_encode($result));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => $errorMessage]))->withStatus(500);
    }
});
/**
 * Get the hotels that are booked by all users for at least once
 *
 * response: [
 * {
 * "id": "1",
 * "brand_name": "Walter Gage",
 * "branch_name": "UBC",
 * "property_class": "3",
 * "address_id": "3",
 * "description": "Walter Gage is known for its positive energy and superb location. Three high-rise towers are conveniently located near the The Nest, bus loop, and many campus recreational facilities.",
 * "overall_rating": "good",
 * "phone_number": "6048221020"
 * }
 * ]
 */
$app->get('/api/stats/hotels-booked-by-all-users', function (Request $request, Response $response) {

    $query = "  SELECT
                    *
                FROM
                    hotel h
                WHERE NOT EXISTS
                    (
                    SELECT
                        username
                    FROM
                        customer
                    WHERE
                        username NOT IN(
                        SELECT
                            c.username
                        FROM
                            customer c,
                            reservation r,
                            reservation_hotel rh
                        WHERE
                            r.username = c.username AND rh.reservation_id = r.id AND rh.hotel_id = h.id
                    )
                )";

    $db = new db();
    $db = $db->connect();

    try {
        $stmt = $db->query($query);

        $hotels = $stmt->fetchAll(PDO::FETCH_OBJ);

        $db = null;

        $response->write(json_encode($hotels));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => $errorMessage]))->withStatus(500);
    }
});

/**
 * Return all users that have booked all hotels for at least once
 *
 * [
 * {
 * "first_name": "ao",
 * "last_name": "tang",
 * "username": "suiyobi",
 * "password": "123",
 * "email": "123",
 * "phone": "",
 * "address_id": "2",
 * "points": "0"
 * }
 * ]
 */
$app->get('/api/stats/users-booked-all-hotels', function (Request $request, Response $response) {

    $query = "  SELECT
                    *
                FROM
                    customer cu
                WHERE NOT EXISTS
                    (
                    SELECT
                        id
                    FROM
                        hotel
                    WHERE
                        id NOT IN(
                        SELECT
                            rh.hotel_id
                        FROM
                            customer c,
                            reservation r,
                            reservation_hotel rh
                        WHERE
                            r.username = c.username AND rh.reservation_id = r.id AND c.username = cu.username
                    )
                )";

    $db = new db();
    $db = $db->connect();

    try {
        $stmt = $db->query($query);

        $hotels = $stmt->fetchAll(PDO::FETCH_OBJ);

        $db = null;

        $response->write(json_encode($hotels));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => $errorMessage]))->withStatus(500);
    }
});