<?php

require_once './controllers/Transaction/TransactionReturnItemsController.php'; // Include the controller

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$transactionReturnItemsController = new TransactionReturnItemsController($pdo);

// Define routes for transaction_return_items
return [
    'GET /return_items/' => function() use ($transactionReturnItemsController) {
        $transactionReturnItemsController->getAllRecords();
    },
    'GET /return_items/{id}/' => function($id) use ($transactionReturnItemsController) {
        $transactionReturnItemsController->getRecordById($id);
    },
    'POST /return_items/' => function() use ($transactionReturnItemsController) {
        $transactionReturnItemsController->createRecord();
    },
    'PUT /return_items/{id}/' => function($id) use ($transactionReturnItemsController) {
        $transactionReturnItemsController->updateRecord($id);
    },
    'DELETE /return_items/{id}/' => function($id) use ($transactionReturnItemsController) {
        $transactionReturnItemsController->deleteRecord($id);
    }
];
