<?php

function mockApiBook($data) {
    $barcode = $data['barcode'];

    // Проверка на уникальность штрих-кода
    $existingBarcodes = ['11111111', '22222222', '33333333'];
    if (in_array($barcode, $existingBarcodes)) {
        return ['error' => 'barcode already exists'];
    }

    return ['message' => 'order successfully booked'];
}

function mockApiApprove($barcode) {
    $errors = ['event cancelled', 'no tickets', 'no seats', 'fan removed'];
    $randomError = $errors[array_rand($errors)];

    if (rand(0, 1) === 1) {
        return ['message' => 'order successfully approved'];
    } else {
        return ['error' => $randomError];
    }
}