<?php

require_once './controllers/Transaction/TransactionExpensesTypesController.php'; // Include the TransactionExpensesTypesController

// Instantiate the controller
$pdo = $GLOBALS['pdo']; // Assuming $pdo is set in the global scope
$transactionExpensesTypesController = new TransactionExpensesTypesController($pdo); // Instantiate the controller

// Define routes for transaction expenses types
return [
    'GET /transaction-expenses-types/' => function() use ($transactionExpensesTypesController) {
        $transactionExpensesTypesController->getAllRecords();
    },
    'GET /transaction-expenses-types/{id}/' => function($id) use ($transactionExpensesTypesController) {
        $transactionExpensesTypesController->getRecordById($id);
    },
    'POST /transaction-expenses-types/' => function() use ($transactionExpensesTypesController) {
        $transactionExpensesTypesController->createRecord();
    },
    'PUT /transaction-expenses-types/{id}/' => function($id) use ($transactionExpensesTypesController) {
        $transactionExpensesTypesController->updateRecord($id);
    },
    'DELETE /transaction-expenses-types/{id}/' => function($id) use ($transactionExpensesTypesController) {
        $transactionExpensesTypesController->deleteRecord($id);
    }
];
?>
