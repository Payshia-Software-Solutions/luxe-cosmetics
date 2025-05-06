<?php

require_once './controllers/Transaction/TransactionReturnController.php'; // Include the controller

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$transactionReturnController = new TransactionReturnController($pdo);

// Define routes for transaction_return
return [
    'GET /returns/' => function() use ($transactionReturnController) {
        $transactionReturnController->getAllRecords();
    },
    'GET /returns/{id}/' => function($id) use ($transactionReturnController) {
        $transactionReturnController->getRecordById($id);
    },
    'POST /returns/' => function() use ($transactionReturnController) {
        $transactionReturnController->createRecord();
    },
    'PUT /returns/{id}/' => function($id) use ($transactionReturnController) {
        $transactionReturnController->updateRecord($id);
    },
    'DELETE /returns/{id}/' => function($id) use ($transactionReturnController) {
        $transactionReturnController->deleteRecord($id);
    }
];
