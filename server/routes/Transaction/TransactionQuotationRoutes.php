<?php

require_once './controllers/Transaction/TransactionQuotationController.php'; // Include the TransactionQuotationController

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$quotationController = new TransactionQuotationController($pdo); // Instantiate the TransactionQuotationController

// Define routes for transaction quotations
return [
    'GET /quotations/' => function() use ($quotationController) {
        $quotationController->getAllRecords();
    },
    'GET /quotations/{id}/' => function($id) use ($quotationController) {
        $quotationController->getRecordById($id);
    },
    'POST /quotations/' => function() use ($quotationController) {
        $quotationController->createRecord();
    },
    'PUT /quotations/{id}/' => function($id) use ($quotationController) {
        $quotationController->updateRecord($id);
    },
    'DELETE /quotations/{id}/' => function($id) use ($quotationController) {
        $quotationController->deleteRecord($id);
    }
];
