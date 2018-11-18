<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 2018/11/11
 * Time: 16:44
 */

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

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
        return $response->write(json_encode(['error' => $errorMessage]))->withStatus(500);
    }
});

/**
 * response: {
 * "reviews": [
 * {
 * "id": "pWFYtKT6BYLEVqe8eaMOHw",
 * "url": "https://www.yelp.com/biz/market-garden-and-express-o-coffee-bar-toronto?hrid=pWFYtKT6BYLEVqe8eaMOHw&adjust_creative=GYyrhVIgBtHGr-1d1-SBFw&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_reviews&utm_source=GYyrhVIgBtHGr-1d1-SBFw",
 * "text": "Market Garden is nested in Eaton Chelsea in the heart of downtown Toronto.\n\nThe cafe offers a seasonal self-served salad bar. For $5.50 you can create your...",
 * "rating": 4,
 * "time_created": "2014-10-04 07:42:54",
 * "user": {
 * "id": "7_RaCe5zzPBYWm9znlffUA",
 * "profile_url": "https://www.yelp.com/user_details?userid=7_RaCe5zzPBYWm9znlffUA",
 * "image_url": "https://s3-media4.fl.yelpcdn.com/photo/zqNqGSUy2l-3qnqA0CqPlA/o.jpg",
 * "name": "Joyce L."
 * }
 * },
 * {
 * "id": "v-LDPBA8o6sPP_0mfr97IQ",
 * "url": "https://www.yelp.com/biz/market-garden-and-express-o-coffee-bar-toronto?hrid=v-LDPBA8o6sPP_0mfr97IQ&adjust_creative=GYyrhVIgBtHGr-1d1-SBFw&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_reviews&utm_source=GYyrhVIgBtHGr-1d1-SBFw",
 * "text": "I was very pleased with my second visit to the Market Grill / Espresso tonight after a long day at Mount Sinai. The salad bar was very fresh, offered a...",
 * "rating": 4,
 * "time_created": "2016-11-30 21:01:33",
 * "user": {
 * "id": "Ld7OI2uAtSCzUqG69h55pw",
 * "profile_url": "https://www.yelp.com/user_details?userid=Ld7OI2uAtSCzUqG69h55pw",
 * "image_url": "https://s3-media3.fl.yelpcdn.com/photo/lovqU2t43Hgtcizue78RKA/o.jpg",
 * "name": "Lynne S."
 * }
 * }
 * ],
 * "total": 2,
 * "possible_languages": [
 * "en"
 * ]
 * }
 */
$app->get('/api/reviews/yelp/hotel/{hotel_id}', function (Request $request, Response $response, array $args) {
    $hotel_id = $args['hotel_id'];

    $findPhoneNumberQuery = "SELECT * FROM hotel WHERE hotel.id = :hotel_id";

    $db = new db();
    $db = $db->connect();

    try {
        $stmt = $db->prepare($findPhoneNumberQuery);
        $stmt->bindParam("hotel_id", $hotel_id);
        $stmt->execute();

        $hotel = $stmt->fetch(PDO::FETCH_OBJ);

        $db = null;

        $yelpReviews = getYelpReviews($hotel->phone_number);

        return $response->write($yelpReviews)->withStatus(200);
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => $errorMessage]))->withStatus(500);
    }
});

// Get All reviews by customer
$app->get('/api/reviews/username/{username}', function (Request $request, Response $response, array $args) {
    $username = $args['username'];
    $sql = "SELECT r.id, r.username, r.rating, r.comment, r.hotel_id, h.brand_name, h.branch_name " .
        " FROM review r, hotel h WHERE username = :username AND h.id = r.hotel_id ORDER BY h.branch_name";
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
        return $response->write(json_encode(['error' => $errorMessage]))->withStatus(500);
    }
});

//Post a new review
$app->post('/api/reviews/create', function (Request $request, Response $response) {
    $username = $request->getParam('username');
    $hotel_id = $request->getParam('hotel_id');
    $rating = $request->getParam('rating');
    $comment = $request->getParam('comment');
    $sql = "INSERT INTO review(username, hotel_id, rating, comment) VALUES (:username, :hotel_id, :rating, :comment)";
    // prepare query
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':hotel_id', $hotel_id);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);
        if ($stmt->execute()) {
            $responseArray = array();
            $id = $db->lastInsertId();
            $db = null;
            $responseArray["id"] = $id;
            return $response->write(json_encode($responseArray))->withStatus(200);
        } else {
            $db = null;
            return $response->write(json_encode(['error' => 'fail to create new reviews']))->withStatus(500);
        }
    } catch (PDOException $e) {
        $db = null;
        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => $errorMessage]))->withStatus(500);
    }
});

/**
 * A helper function that integrates with Yelp Fusion API that fetches real user reviews data for a hotel
 * @return mixed|string
 */
function getYelpReviews($phone_number)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.yelp.com/v3/businesses/search/phone?phone=+1$phone_number",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer -8QTaVKIzFUegkjcLdWeZI9kcmMRCdJrWAPFu9CrdUyi9FPdbDRReFcdoeZHMfqZRE8465UgMQJ2JGtCfsYyaQebNP0EZo1k8gEbfBhDI_GdpZmUsMJVSwA-PSftW3Yx",
            "Postman-Token: fb759202-9677-47cf-ab4e-6d6e9626be5e",
            "cache-control: no-cache"
        ),
    ));

    $res = curl_exec($curl);
    $err = curl_error($curl);

    if ($err) {
        curl_close($curl);
        return "cURL Error #:" . $err;
    }

    $array = json_decode($res);

    $id = $array->businesses[0]->id;

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.yelp.com/v3/businesses/$id/reviews",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer -8QTaVKIzFUegkjcLdWeZI9kcmMRCdJrWAPFu9CrdUyi9FPdbDRReFcdoeZHMfqZRE8465UgMQJ2JGtCfsYyaQebNP0EZo1k8gEbfBhDI_GdpZmUsMJVSwA-PSftW3Yx",
            "Postman-Token: 7c33349d-ad3d-466e-b59e-cc3621f5235b",
            "cache-control: no-cache"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return "cURL Error #:" . $err;
    }
    return $response;
}