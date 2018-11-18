<?php
/**
 * Created by PhpStorm.
 * User: ao
 * Date: 2018-11-11
 * Time: 11:11 AM
 */

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$address_columns = 'a.id as address_id, a.country, a.province, a.city, a.postal_code, a.street';
$hotel_columns = 'h.id as id, h.brand_name, h.branch_name, h.property_class, h.description, h.phone_number';

/**
 * Core business logic on finding the hotels that have available rooms given an address, number of rooms requested,
 * checkin date and checkout date.
 *
 * Example GET params
 *       city?=Vancouver (optional)
 *       province?=British Columbia (optional)
 *       country?=Canada (optional)
 *       checkin_date: 2018-11-11
 *       checkout_date: "2018-12-12
 *       rooms: 2
 *
 * Example Response
 * [
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
 *
 */
$app->get('/api/hotels/availabilities', function (Request $request, Response $response) {

    $params = $request->getQueryParams();

    $city = array_key_exists('city', $params) ? $params['city'] : null;
    $province = array_key_exists('province', $params) ? $params['province'] : null;
    $country = array_key_exists('country', $params) ? $params['country'] : null;;

    $cityCondition = "";
    $provinceCondition = "";
    $countryCondition = "";

    if ($city) {
        $cityCondition = " a.city = '$city' AND ";
    }

    if ($province) {
        $provinceCondition = "a.province = '$province' AND ";
    }

    if ($country) {
        $countryCondition = "a.country = '$country' AND ";
    }

    $availabilityQueryTemplate = "SELECT h.*, a.street, a.country, a.province, a.city FROM room r, hotel h, address a 
              WHERE 
              r.hotel_id = h.id AND 
              h.address_id = a.id AND 
              $cityCondition
              $provinceCondition
              $countryCondition
              r.id NOT IN
                (SELECT DISTINCT rr.room_id FROM reservation_room rr 
                WHERE 
                  (rr.checkout_date > :checkin_date AND rr.checkout_date < :checkout_date) OR 
                  (rr.checkin_date > :checkin_date AND rr.checkin_date < :checkout_date) OR 
                  (rr.checkin_date <= checkin_date AND rr.checkout_date >= :checkout_date))
              GROUP BY h.id
              HAVING COUNT(*) >= :rooms";

    $db = new db();
    $db = $db->connect();

    try {
        $stmt = $db->prepare($availabilityQueryTemplate);

        $stmt->bindParam('checkin_date', $params['checkin_date']);
        $stmt->bindParam('checkout_date', $params['checkout_date']);
        $stmt->bindParam('rooms', $params['rooms']);

        $stmt->execute();

        $hotels = $stmt->fetchAll(PDO::FETCH_OBJ);

        $db = null;

        return $response->write(json_encode($hotels))->withStatus(200);
    } catch (PDOException $e) {
        $db = null;

        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => '' . $errorMessage]))->withStatus(500);
    }
});

/**
 * Return all the available rooms of a hotel
 * Example GET params
 *      checkin_date:2018-11-11
 *      checkout_date:2019-01-02
 * Example response
 * [
 * {
 * "id": "20",
 * "hotel_id": "1",
 * "room_type_id": "36"
 * }
 * ]
 * Note: at this point, we will no longer care about the number of rooms because the hotel you request for should already
 * meet the requirement of having enough rooms for the request.
 */
$app->get('/api/hotels/{id}/availabilities', function (Request $request, Response $response, array $args) {

    $params = $request->getQueryParams();

    $hotelAvailability = "SELECT r.*, rt.type_name, rt.occupancy, rt.description, rt.price from hotel h, room r, room_type rt 
                          WHERE
                              h.id = :id AND
                              r.hotel_id = h.id AND
                              r.room_type_id = rt.id AND 
                              r.id NOT IN 
                                (SELECT DISTINCT rr.room_id FROM reservation_room rr 
                                WHERE 
                                   (rr.checkout_date > :checkin_date AND rr.checkout_date < :checkout_date) OR 
                                   (rr.checkin_date > :checkin_date AND rr.checkin_date < :checkout_date) OR 
                                   (rr.checkin_date <= checkin_date AND rr.checkout_date >= :checkout_date))";

    $db = new db();
    $db = $db->connect();

    try {
        $stmt = $db->prepare($hotelAvailability);

        $stmt->bindParam('id', $args['id']);
        $stmt->bindParam('checkin_date', $params['checkin_date']);
        $stmt->bindParam('checkout_date', $params['checkout_date']);

        $stmt->execute();

        $hotels = $stmt->fetchAll(PDO::FETCH_OBJ);

        $db = null;

        return $response->write(json_encode($hotels))->withStatus(200);
    } catch (PDOException $e) {
        $db = null;

        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => '' . $errorMessage]))->withStatus(500);
    }
});

$app->get('/api/hotels/{id}', function (Request $request, Response $response, array $args) {

    $hotel_columns = $GLOBALS['hotel_columns'];
    $address_columns = $GLOBALS['address_columns'];

    $id = $args['id'];

    $query = "SELECT $hotel_columns,$address_columns FROM hotel h INNER JOIN address a ON h.address_id = a.id WHERE h.id = $id";

    try {
        $db = new db();

        $db = $db->connect();
        $stmt = $db->query($query);

        $hotel = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;

        if (empty($hotel)) {
            return $response->write('{"error": {"text": "No hotel found for id = ' . $id . '"}}')->withStatus(404);
        }

        $response->write(json_encode($hotel));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;

        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => '' . $errorMessage]))->withStatus(500);
    }
});

// Get All hotels order by branch name
$app->get('/api/hotels', function (Request $request, Response $response) {

    $hotel_columns = $GLOBALS['hotel_columns'];
    $address_columns = $GLOBALS['address_columns'];

    $query = "SELECT $hotel_columns,$address_columns FROM hotel h INNER JOIN address a ON h.address_id = a.id ORDER BY h.branch_name";

    try {
        $db = new db();

        $db = $db->connect();
        $stmt = $db->query($query);

        $hotels = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $response->write(json_encode($hotels));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;

        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => '' . $errorMessage]))->withStatus(500);
    }
});

$app->post('/api/hotels/create', function (Request $request, Response $response) {

    $parsedBody = $request->getParsedBody();

    $createAddressQuery = "INSERT INTO address (country,province,city, street, postal_code)" .
        "VALUES (:country, :province, :city, :street, :postal_code)";
    $createHotelQuery = "INSERT INTO hotel (brand_name, branch_name, property_class, address_id, description, overall_rating, phone_number)" .
        " VALUES (:brand_name,:branch_name,:property_class,:address_id,:description,:overall_rating, :phone_number)";

    $db = new db();
    $db = $db->connect();

    try {
        $db->beginTransaction();

        $stmt = $db->prepare($createAddressQuery);

        $stmt->bindParam(":country", $parsedBody["country"]);
        $stmt->bindParam(":city", $parsedBody["city"]);
        $stmt->bindParam(":province", $parsedBody["province"]);
        $stmt->bindParam(":street", $parsedBody["street"]);
        $stmt->bindParam(":postal_code", $parsedBody["postal_code"]);

        if ($stmt->execute()) {
            $insertedId = $db->lastInsertId();

            $stmt = $db->prepare($createHotelQuery);

            $stmt->bindParam(":brand_name", $parsedBody["brand_name"]);
            $stmt->bindParam(":branch_name", $parsedBody["branch_name"]);
            $stmt->bindParam(":property_class", $parsedBody["property_class"]);
            $stmt->bindParam(":address_id", $insertedId);
            $stmt->bindParam(":description", $parsedBody["description"]);
            $stmt->bindParam(":overall_rating", $parsedBody["overall_rating"]);
            $stmt->bindParam(":phone_number", $parsedBody["phone_number"]);

            if ($stmt->execute()) {
                $insertedId = $db->lastInsertId();
                $db->commit();
                $db = null;

                $responseArray = array();
                $responseArray["id"] = $insertedId;

                return $response->write(json_encode($responseArray))->withStatus(200);
            } else {
                $response->write('{"error": {"text": "failed create on hotel"}}');
            }
        } else {
            $response->write('{"error": {"text": "failed create on address"}}');
        }

        $db = null;
        return $response->withStatus(500);
    } catch (PDOException $e) {
        $db->rollBack();
        $db = null;

        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => '' . $errorMessage]))->withStatus(500);
    }
});

$app->put('/api/hotels/update', function (Request $request, Response $response) {

    $parsedBody = $request->getParsedBody();

    $updateQuery = 'UPDATE hotel 
        SET 
            brand_name = :brand_name,
            branch_name = :branch_name,
            property_class = :property_class,
            address_id = :address_id,
            description = :description,
            overall_rating = :overall_rating,
            phone_number = :phone_number
         WHERE id = :id';

    $db = new db();
    $db = $db->connect();

    try {
        $db->beginTransaction();

        $stmt = $db->prepare($updateQuery);

        $stmt->bindParam(":brand_name", $parsedBody["brand_name"]);
        $stmt->bindParam(":branch_name", $parsedBody["branch_name"]);
        $stmt->bindParam(":property_class", $parsedBody["property_class"]);
        $stmt->bindParam(":address_id", $parsedBody["address_id"]);
        $stmt->bindParam(":description", $parsedBody["description"]);
        $stmt->bindParam(":overall_rating", $parsedBody["overall_rating"]);
        $stmt->bindParam(":phone_number", $parsedBody["phone_number"]);
        $stmt->bindParam("id", $parsedBody["id"]);

        if ($stmt->execute()) {
            $db->commit();
            $db = null;

            return $response->withStatus(200);
        } else {
            $db = null;

            $response->write('{"error": {"text": "failed update on Hotel"}}');
            return $response->withStatus(500);
        }
    } catch (PDOException $e) {
        $db->rollBack();
        $db = null;

        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => '' . $errorMessage]))->withStatus(500);
    }

});

$app->delete('/api/hotels/delete/{id}', function (Request $request, Response $response, array $args) {

    $hotel_columns = $GLOBALS['hotel_columns'];
    $address_columns = $GLOBALS['address_columns'];

    $id = $args['id'];

    $deleteAddressQuery = "DELETE FROM address WHERE id = :address_id";
    $findByIdQuery = "SELECT $hotel_columns,$address_columns FROM hotel h INNER JOIN address a ON h.address_id = a.id WHERE h.id = $id";

    $db = new db();
    $db = $db->connect();

    try {
        $db->beginTransaction();

        $stmt = $db->query($findByIdQuery);

        $hotel = $stmt->fetch(PDO::FETCH_OBJ);

        if ($hotel) {
            $stmt = $db->prepare($deleteAddressQuery);
            $stmt->bindParam('address_id', $hotel->address_id);

            if ($stmt->execute()) {
                $db->commit();
                $db = null;

                return $response->withStatus(200);
            } else {
                $db = null;

                $response->write('{"error": {"text": "failed delete on Hotel"}}');
                return $response->withStatus(500);
            }
        } else {
            $db = null;

            return $response->write('{"error": {"text": "No hotel found for id = ' . $id . '"}}')->withStatus(404);
        }
    } catch (PDOException $e) {
        $db->rollBack();
        $db = null;

        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => '' . $errorMessage]))->withStatus(500);
    }
});

// search hotels by branch name and brand name
$app->get('/api/hotels/feature/search', function (Request $request, Response $response) {

    $brandName = $request->getParam('brandName');
    $branchName = $request->getParam('branchName');

    $hotel_columns = $GLOBALS['hotel_columns'];
    $address_columns = $GLOBALS['address_columns'];

    if ($brandName && $branchName) {
        $sql = "SELECT $hotel_columns, $address_columns " .
            "FROM hotel h, address a " .
            "WHERE h.brand_name = '$brandName' " .
            "AND h.branch_name = '$branchName' " .
            "AND h.address_id = a.id ";
    } else if ($branchName && !$brandName) {
        $sql = "SELECT $hotel_columns, $address_columns " .
            "FROM hotel h, address a " .
            "WHERE h.branch_name = '$branchName' " .
            "AND h.address_id = a.id ";
    } else if (!$branchName && $brandName) {
        $sql = "SELECT $hotel_columns, $address_columns " .
            "FROM hotel h, address a " .
            "WHERE h.brand_name = '$brandName' " .
            "AND h.address_id = a.id ";
    } else {
        // no params, bad request
        return $response->withStatus(400);
    }
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $hotels = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $response->write(json_encode($hotels));
        return $response->withStatus(200);
    } catch (PDOException $e) {
        $db = null;

        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => '' . $errorMessage]))->withStatus(500);
    }
});