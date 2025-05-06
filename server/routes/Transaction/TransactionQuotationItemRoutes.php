<?php

require_once './controllers/Transaction/TransactionQuotationItemController.php'; // Include the controller

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$transactionQuotationItemController = new TransactionQuotationItemController($pdo); // Instantiate the controller

// Define routes for transaction quotation items
return [
    'GET /quotation-items/' => function() use ($transactionQuotationItemController) {
        $transactionQuotationItemController->getAllRecords(); // Fetch all items
    },
    'GET /quotation-items/{item_id}/' => function($item_id) use ($transactionQuotationItemController) {
        $transactionQuotationItemController->getRecordById($item_id); // Fetch item by ID
    },
    'POST /quotation-items/' => function() use ($transactionQuotationItemController) {
        $transactionQuotationItemController->createRecord(); // Create a new item
    },
    'PUT /quotation-items/{item_id}/' => function($item_id) use ($transactionQuotationItemController) {
        $transactionQuotationItemController->updateRecord($item_id); // Update item by ID
    },
    'DELETE /quotation-items/{item_id}/' => function($item_id) use ($transactionQuotationItemController) {
        $transactionQuotationItemController->deleteRecord($item_id); // Delete item by ID
    }
];
