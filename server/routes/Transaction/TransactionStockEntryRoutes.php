<?php

require_once './controllers/Transaction/TransactionStockEntryController.php'; // Include the controller

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$transactionStockEntryController = new TransactionStockEntryController($pdo);

// Define routes for transaction_stock_entry
return [
    'GET /stock_entries/' => function() use ($transactionStockEntryController) {
        $transactionStockEntryController->getAllRecords();
    },
    'GET /stock_entries/{id}/' => function($id) use ($transactionStockEntryController) {
        $transactionStockEntryController->getRecordById($id);
    },
    'POST /stock_entries/' => function() use ($transactionStockEntryController) {
        $transactionStockEntryController->createRecord();
    },
    'PUT /stock_entries/{id}/' => function($id) use ($transactionStockEntryController) {
        $transactionStockEntryController->updateRecord($id);
    },
    'DELETE /stock_entries/{id}/' => function($id) use ($transactionStockEntryController) {
        $transactionStockEntryController->deleteRecord($id);
    }
];
