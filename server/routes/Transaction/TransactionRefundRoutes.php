<?php

require_once './controllers/Transaction/TransactionRefundController.php'; // Include the controller

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$refundController = new TransactionRefundController($pdo); // Initialize the controller with the PDO object

// Define routes for transaction_refund
return [
    'GET /refunds/' => function() use ($refundController) {
        $refundController->getAllRecords();
    },
    'GET /refunds/{id}/' => function($id) use ($refundController) {
        $refundController->getRecordById($id); // Pass the refund ID to fetch a specific refund
    },
    'POST /refunds/' => function() use ($refundController) {
        $refundController->createRecord(); // Create a new refund
    },
    'PUT /refunds/{id}/' => function($id) use ($refundController) {
        $refundController->updateRecord($id); // Update an existing refund by ID
    },
    'DELETE /refunds/{id}/' => function($id) use ($refundController) {
        $refundController->deleteRecord($id); // Delete a refund by ID
    }
];

?>
