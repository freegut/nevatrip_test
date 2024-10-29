<?php

class OrderService {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function generateUniqueBarcode($length = 8) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function bookOrder($eventId, $eventDate, $ticketAdultPrice, $ticketAdultQuantity, $ticketKidPrice, $ticketKidQuantity, $ticketDiscountPrice, $ticketDiscountQuantity, $ticketGroupPrice, $ticketGroupQuantity) {
        $barcode = $this->generateUniqueBarcode();
        $data = [
            'event_id' => $eventId,
            'event_date' => $eventDate,
            'ticket_adult_price' => $ticketAdultPrice,
            'ticket_adult_quantity' => $ticketAdultQuantity,
            'ticket_kid_price' => $ticketKidPrice,
            'ticket_kid_quantity' => $ticketKidQuantity,
            'ticket_discount_price' => $ticketDiscountPrice,
            'ticket_discount_quantity' => $ticketDiscountQuantity,
            'ticket_group_price' => $ticketGroupPrice,
            'ticket_group_quantity' => $ticketGroupQuantity,
            'barcode' => $barcode
        ];

        $response = mockApiBook($data);
        while (isset($response['error'])) {
            $barcode = $this->generateUniqueBarcode();
            $data['barcode'] = $barcode;
            $response = mockApiBook($data);
        }

        $equalPrice = ($ticketAdultPrice * $ticketAdultQuantity) + ($ticketKidPrice * $ticketKidQuantity) + ($ticketDiscountPrice * $ticketDiscountQuantity) + ($ticketGroupPrice * $ticketGroupQuantity);
        $created = date('Y-m-d H:i:s');

        $stmt = $this->conn->prepare("INSERT INTO orders (event_id, event_date, equal_price, created) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $eventId, $eventDate, $equalPrice, $created);
        $stmt->execute();
        $orderId = $stmt->insert_id;

        $this->insertTickets($orderId, 1, $ticketAdultPrice, $ticketAdultQuantity);
        $this->insertTickets($orderId, 2, $ticketKidPrice, $ticketKidQuantity);
        $this->insertTickets($orderId, 3, $ticketDiscountPrice, $ticketDiscountQuantity);
        $this->insertTickets($orderId, 4, $ticketGroupPrice, $ticketGroupQuantity);

        return $barcode;
    }

    private function insertTickets($orderId, $ticketTypeId, $price, $quantity) {
        for ($i = 0; $i < $quantity; $i++) {
            $barcode = $this->generateUniqueBarcode();
            $stmt = $this->conn->prepare("INSERT INTO tickets (order_id, ticket_type_id, price, barcode) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiis", $orderId, $ticketTypeId, $price, $barcode);
            $stmt->execute();
        }
    }

    public function approveOrder($barcode) {
        $response = mockApiApprove($barcode);
        if (isset($response['message'])) {
            return true;
        } else {
            return false;
        }
    }
}