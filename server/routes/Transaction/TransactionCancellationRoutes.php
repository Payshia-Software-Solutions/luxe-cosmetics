<?php

require_once './controllers/Transaction/TransactionCancellationController.php'; // Include the TransactionCancellationController

// Instantiate the controller
$pdo = $GLOBALS['pdo']; // Assuming PDO connection is stored globally
$transactionCancellationController = new TransactionCancellationController($pdo); // Instantiate the controller

// Define routes for transaction cancellations
return [
    'GET /cancellations/' => function() use ($transactionCancellationController) {
        $transactionCancellationController->getAllRecords();
    },
    'GET /cancellations/{id}/' => function($id) use ($transactionCancellationController) { // Pass id directly
        $transactionCancellationController->getRecordById($id); // Pass id to the method
    },
    'POST /cancellations/' => function() use ($transactionCancellationController) {
        $transactionCancellationController->createRecord();
    },
    'PUT /cancellations/{id}/' => function($id) use ($transactionCancellationController) {
        $transactionCancellationController->updateRecord($id); // Pass id directly
    },
    'DELETE /cancellations/{id}/' => function($id) use ($transactionCancellationController) {
        $transactionCancellationController->deleteRecord($id); // Pass id directly
    }
];
