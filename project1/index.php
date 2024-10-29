<?php

require_once 'config/db.php';
require_once 'api/mock_api.php';
require_once 'src/OrderService.php';

$conn = require 'config/db.php';
$orderService = new OrderService($conn);

$eventId = 123;
$eventDate = '2023-10-10 18:00:00';
$ticketAdultPrice = 1000;
$ticketAdultQuantity = 2;
$ticketKidPrice = 500;
$ticketKidQuantity = 1;

$barcode = $orderService->bookOrder($eventId, $eventDate, $ticketAdultPrice, $ticketAdultQuantity, $ticketKidPrice, $ticketKidQuantity);

if ($orderService->approveOrder($barcode)) {
    echo "Order successfully approved with barcode: $barcode";
} else {
    echo "Order approval failed with barcode: $barcode";
}