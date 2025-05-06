<?php

require_once './controllers/Transaction/TransactionInvoiceController.php'; // Include the TransactionInvoiceController

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$transactionInvoiceController = new TransactionInvoiceController($pdo); // Instantiate the TransactionInvoiceController

// Define routes for transaction invoices
return [
    'GET /invoices/' => function () use ($transactionInvoiceController) {
        $transactionInvoiceController->getAllRecords();
    },
    'GET /invoices/new-number/{prefix}' => function ($prefix) use ($transactionInvoiceController) {
        $transactionInvoiceController->generateInvoiceNumber($prefix);
    },
    'GET /invoices/{invoice_id}/' => function ($invoice_id) use ($transactionInvoiceController) { // Pass invoice_id directly
        $transactionInvoiceController->getRecordById($invoice_id); // Pass invoice_id to the method
    },
    'POST /invoices/' => function () use ($transactionInvoiceController) {
        $transactionInvoiceController->createRecord();
    },
    'PUT /invoices/{invoice_id}/' => function ($invoice_id) use ($transactionInvoiceController) {
        $transactionInvoiceController->updateRecord($invoice_id); // Pass invoice_id directly
    },
    'DELETE /invoices/{invoice_id}/' => function ($invoice_id) use ($transactionInvoiceController) {
        $transactionInvoiceController->deleteRecord($invoice_id); // Pass invoice_id directly
    }
];
