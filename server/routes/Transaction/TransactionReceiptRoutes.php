<?php

require_once './controllers/Transaction/TransactionReceiptController.php'; // Include the controller

// Instantiate the controller
$pdo = $GLOBALS['pdo']; // Ensure that $pdo is properly initialized with the database connection
$transactionReceiptController = new TransactionReceiptController($pdo);

// Define routes for transaction receipts
return [
    'GET /transaction-receipts/' => function() use ($transactionReceiptController) {
        $transactionReceiptController->getAllRecords(); // Fetch all receipts
    },
    'GET /transaction-receipts/{receipt_id}/' => function($receipt_id) use ($transactionReceiptController) {
        $transactionReceiptController->getRecordById($receipt_id); // Fetch receipt by ID
    },
    'POST /transaction-receipts/' => function() use ($transactionReceiptController) {
        $transactionReceiptController->createRecord(); // Create a new receipt
    },
    'PUT /transaction-receipts/{receipt_id}/' => function($receipt_id) use ($transactionReceiptController) {
        $transactionReceiptController->updateRecord($receipt_id); // Update receipt by ID
    },
    'DELETE /transaction-receipts/{receipt_id}/' => function($receipt_id) use ($transactionReceiptController) {
        $transactionReceiptController->deleteRecord($receipt_id); // Delete receipt by ID
    }
];
