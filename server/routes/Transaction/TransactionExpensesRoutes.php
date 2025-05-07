<?php

require_once './controllers/Transaction/TransactionExpensesController.php'; // Include the TransactionExpensesController

// Instantiate the controller
$pdo = $GLOBALS['pdo']; // Assuming PDO connection is stored globally
$transactionExpensesController = new TransactionExpensesController($pdo); // Instantiate the controller

// Define routes for transaction expenses
return [
    'GET /expenses/' => function() use ($transactionExpensesController) {
        $transactionExpensesController->getAllRecords();
    },
    'GET /expenses/{id}/' => function($id) use ($transactionExpensesController) { // Pass id directly
        $transactionExpensesController->getRecordById($id); // Pass id to the method
    },
    'POST /expenses/' => function() use ($transactionExpensesController) {
        $transactionExpensesController->createRecord();
    },
    'PUT /expenses/{id}/' => function($id) use ($transactionExpensesController) {
        $transactionExpensesController->updateRecord($id); // Pass id directly
    },
    'DELETE /expenses/{id}/' => function($id) use ($transactionExpensesController) {
        $transactionExpensesController->deleteRecord($id); // Pass id directly
    }
];
