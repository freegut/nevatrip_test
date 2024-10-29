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

    public function bookOrder($eventId, $eventDate, $ticketAdultPrice, $ticketAdultQuantity, $ticketKidPrice, $ticketKidQuantity) {
        $barcode = $this->generateUniqueBarcode();
        $data = [
            'event_id' => $eventId,
            'event_date' => $eventDate,
            'ticket_adult_price' => $ticketAdultPrice,
            'ticket_adult_quantity' => $ticketAdultQuantity,
            'ticket_kid_price' => $ticketKidPrice,
            'ticket_kid_quantity' => $ticketKidQuantity,
            'barcode' => $barcode
        ];

        $response = mockApiBook($data);
        while (isset($response['error'])) {
            $barcode = $this->generateUniqueBarcode();
            $data['barcode'] = $barcode;
            $response = mockApiBook($data);
        }

        $equalPrice = ($ticketAdultPrice * $ticketAdultQuantity) + ($ticketKidPrice * $ticketKidQuantity);
        $created = date('Y-m-d H:i:s');

        $stmt = $this->conn->prepare("INSERT INTO orders (event_id, event_date, ticket_adult_price, ticket_adult_quantity, ticket_kid_price, ticket_kid_quantity, barcode, equal_price, created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isiiisiss", $eventId, $eventDate, $ticketAdultPrice, $ticketAdultQuantity, $ticketKidPrice, $ticketKidQuantity, $barcode, $equalPrice, $created);
        $stmt->execute();

        return $barcode;
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