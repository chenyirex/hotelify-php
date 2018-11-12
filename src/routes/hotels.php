<?php
/**
 * Created by PhpStorm.
 * User: ao
 * Date: 2018-11-11
 * Time: 11:11 AM
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$address_columns = 'a.id as address_id, a.country, a.province, a.city, a.postal_code, a.street';
$hotel_columns = 'h.id as hotel_id, h.brand_name, h.branch_name, h.property_class, h.description';

// Get All hotels
$app->get('/api/hotels', function (Request $request, Response $response) {

    $sql = "SELECT h.id as hotel_id, a.id as address_id, h.*, a.* FROM hotel h, address a where h.address_id = a.id";
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
        $response->write($e);
        return $response->withStatus(500);
    }
});

// search hotels by branch name and brand name
$app->get('/api/hotels/search', function (Request $request, Response $response) {

    $brandName = $request->getParam('brandName');
    $branchName = $request->getParam('branchName');

    $hotel_columns = $GLOBALS['hotel_columns'];
    $address_columns = $GLOBALS['address_columns'];

    if ($brandName && $branchName) {
        $sql = "SELECT $hotel_columns, $address_columns " .
            "FROM hotel h, address a " .
            "WHERE h.brand_name = '$brandName' " .
            "AND h.branch_name = '$branchName' ";
    } else if ($branchName && !$brandName) {
        $sql = "SELECT $hotel_columns, $address_columns " .
            "FROM hotel h, address a " .
            "WHERE h.branch_name = '$branchName' ";
    } else if (!$branchName && $brandName) {
        $sql = "SELECT $hotel_columns, $address_columns " .
            "FROM hotel h, address a " .
            "WHERE h.brand_name = '$brandName' ";
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
        $response->write($e);
        return $response->withStatus(500);
    }
    echo json_encode($brandName);

});