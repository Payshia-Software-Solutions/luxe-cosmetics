<?php

require_once './controllers/Transaction/TransactionRemovalRemarkController.php'; // Include the controller

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$transactionRemovalRemarkController = new TransactionRemovalRemarkController($pdo);

// Define routes for transaction_removal_remark
return [
    'GET /removal_remarks/' => function() use ($transactionRemovalRemarkController) {
        $transactionRemovalRemarkController->getAllRecords();
    },
    'GET /removal_remarks/{id}/' => function($id) use ($transactionRemovalRemarkController) {
        $transactionRemovalRemarkController->getRecordById($id);
    },
    'POST /removal_remarks/' => function() use ($transactionRemovalRemarkController) {
        $transactionRemovalRemarkController->createRecord();
    },
    'PUT /removal_remarks/{id}/' => function($id) use ($transactionRemovalRemarkController) {
        $transactionRemovalRemarkController->updateRecord($id);
    },
    'DELETE /removal_remarks/{id}/' => function($id) use ($transactionRemovalRemarkController) {
        $transactionRemovalRemarkController->deleteRecord($id);
    }
];
