<?php

require_once './controllers/AddressController.php';

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$addressController = new AddressController($pdo);

// Define routes for addresses
return [
    'POST /addresses/' => function () use ($addressController) {
        $addressController->createAddress();
    },
    'GET /addresses/{invoice_number}/' => function ($invoice_number) use ($addressController) {
        $addressController->getAddresses($invoice_number);
    },
    'DELETE /addresses/{address_id}/' => function ($address_id) use ($addressController) {
        $addressController->deleteAddress($address_id);
    }
];
