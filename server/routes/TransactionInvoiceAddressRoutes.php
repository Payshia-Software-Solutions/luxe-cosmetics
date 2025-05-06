<?php

require_once './controllers/TransactionInvoiceAddressController.php'; // Ensure the correct case in the filename

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$addressController = new TransactionInvoiceAddressController($pdo); // Use consistent variable naming

// Define routes for transaction invoice addresses
return [
    'GET /addresses/' => function () use ($addressController) {
        $addressController->getAllRecords();
    },
    'GET /addresses/{address_id}/' => function ($address_id) use ($addressController) { // Pass address_id as a direct argument
        $addressController->getRecordById($address_id); // Pass address_id directly
    },

    'GET /addresses/by-invoice/{address_id}/' => function ($address_id) use ($addressController) { // Pass address_id as a direct argument
        $addressController->getRecordsByInvoice($address_id); // Pass address_id directly
    },
    'POST /addresses/' => function () use ($addressController) {
        $addressController->createRecord();
    },
    'PUT /addresses/{address_id}/' => function ($address_id) use ($addressController) {
        $addressController->updateRecord($address_id); // Pass address_id directly
    },
    'DELETE /addresses/{address_id}/' => function ($address_id) use ($addressController) {
        $addressController->deleteRecord($address_id); // Pass address_id directly
    }
];
