<?php
require_once(__DIR__ . '/../../confi/db.php');
require_once(__DIR__ . '/../../Model/flight.php');

if (isset($_GET['flight_number'])) {
    $flightNumber = $_GET['flight_number'];

    $flightModel = new Flight($pdo); // استخدمي $pdo من db.php

    if ($flightModel->deleteFlight($flightNumber)) {
        header("Location: manageflight.php?deleted=1");
    } else {
        header("Location: manageflight.php?error=delete_failed");
    }
    exit;
} else {
    header("Location: manageflight.php?error=missing_flight_number");
    exit;
}
?>
