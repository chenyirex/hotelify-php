<?php
/**
 * Created by PhpStorm.
 * User: chenyi
 * Date: 2018-11-16
 * Time: 11:58 AM
 */

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/api/payments/{id}', function (Request $request, Response $response, array $args) {

    $id = $args['id'];

    $query = "SELECT * FROM payment WHERE id = :id";

    $db = new db();
    $db = $db->connect();

    try {
        $stmt = $db->prepare($query);

        $stmt->bindParam('id', $id);
        $stmt->execute();

        $payment = $stmt->fetch(PDO::FETCH_OBJ);

        $db = null;

        return $response->write(json_encode($payment))->withStatus(200);
    } catch (PDOException $e) {
        $db = null;

        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => '' . $errorMessage]))->withStatus(500);
    }
});

$app->post('/api/payments/reservation/{id}', function (Request $request, Response $response, array $args) {

    $parsedBody = $request->getParsedBody();
    $id = $args['id'];

    $paymentInsertQuery = "INSERT INTO payment (amount, coupon_id, card_number) VALUES (:amount, :coupon_id, :card_number)";
    $associateReservationQuery = "UPDATE reservation SET payment_id = :payment_id WHERE id = :id";
    $db = new db();
    $db = $db->connect();


    try {
        $stmt = $db->prepare($paymentInsertQuery);

        $stmt->bindParam('amount', $parsedBody['amount']);
        $stmt->bindParam('coupon_id', $parsedBody['coupon_id']);
        $stmt->bindParam('card_number', $parsedBody['card_number']);

        if ($stmt->execute()) {
            $paymentId = $db->lastInsertId();

            $stmt = $db->prepare($associateReservationQuery);

            $stmt->bindParam('id', $id);
            $stmt->bindParam('payment_id', $paymentId);

            if ($stmt->execute()) {

                $db = null;

                $responseArray = array();
                $responseArray["id"] = $paymentId;

                return $response->write(json_encode($responseArray))->withStatus(200);
            } else {
                $response->withStatus(500);
            }
        } else {
            $response->withStatus(500);
        }
        $db = null;

        return $response;
    } catch (PDOException $e) {
        $db = null;

        $errorMessage = $e->getMessage();
        return $response->write(json_encode(['error' => '' . $errorMessage]))->withStatus(500);
    }
});