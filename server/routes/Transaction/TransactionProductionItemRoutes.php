<?php

require_once './controllers/Transaction/TransactionProductionItemController.php'; // Include the TransactionProductionItemController

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$transactionProductionItemController = new TransactionProductionItemController($pdo); // Instantiate the controller

// Define routes for transaction production items
return [
    'GET /production-items/' => function() use ($transactionProductionItemController) {
        $transactionProductionItemController->getAllRecords();
    },
    'GET /production-items/{id}/' => function($id) use ($transactionProductionItemController) {
        $transactionProductionItemController->getRecordById($id); // Fetch record by ID
    },
    'POST /production-items/' => function() use ($transactionProductionItemController) {
        $transactionProductionItemController->createRecord(); // Create a new production item
    },
    'PUT /production-items/{id}/' => function($id) use ($transactionProductionItemController) {
        $transactionProductionItemController->updateRecord($id); // Update a production item by ID
    },
    'DELETE /production-items/{id}/' => function($id) use ($transactionProductionItemController) {
        $transactionProductionItemController->deleteRecord($id); // Delete a production item by ID
    }
];
?>
