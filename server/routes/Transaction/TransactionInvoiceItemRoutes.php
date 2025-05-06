<?php

require_once './controllers/Transaction/TransactionInvoiceItemController.php';

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$transactionInvoiceItemController = new TransactionInvoiceItemController($pdo); // Instantiate the controller

// Define routes for transaction invoice items
return [
    // Get all transaction invoice items
    'GET /transaction-invoice-items/' => function () use ($transactionInvoiceItemController) {
        $transactionInvoiceItemController->getAllRecords();
    },

    // Get a single transaction invoice item by ID
    'GET /transaction-invoice-items/{item_id}/' => function ($item_id) use ($transactionInvoiceItemController) {
        $transactionInvoiceItemController->getRecordById($item_id);
    },

    // Get a single transaction invoice item by ID
    'GET /transaction-invoice-items/by-invoice/{invoice_number}/' => function ($invoice_number) use ($transactionInvoiceItemController) {
        $transactionInvoiceItemController->getRecordsByInvoice($invoice_number);
    },


    // Create a new transaction invoice item
    'POST /transaction-invoice-items/' => function () use ($transactionInvoiceItemController) {
        $transactionInvoiceItemController->createRecord();
    },

    // Update an existing transaction invoice item by ID
    'PUT /transaction-invoice-items/{item_id}/' => function ($item_id) use ($transactionInvoiceItemController) {
        $transactionInvoiceItemController->updateRecord($item_id);
    },

    // Delete a transaction invoice item by ID
    'DELETE /transaction-invoice-items/{item_id}/' => function ($item_id) use ($transactionInvoiceItemController) {
        $transactionInvoiceItemController->deleteRecord($item_id);
    }
];
