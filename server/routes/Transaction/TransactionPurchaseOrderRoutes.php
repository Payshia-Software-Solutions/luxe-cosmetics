<?php

require_once './controllers/Transaction/TransactionPurchaseOrderController.php'; // Include the TransactionPurchaseOrderController

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$purchaseOrderController = new TransactionPurchaseOrderController($pdo); // Instantiate the TransactionPurchaseOrderController

// Define routes for purchase orders
return [
    'GET /purchase-orders/' => function() use ($purchaseOrderController) {
        $purchaseOrderController->getAllRecords();
    },
    'GET /purchase-orders/{id}/' => function($id) use ($purchaseOrderController) { // Pass ID directly
        $purchaseOrderController->getRecordById($id); // Pass ID to the method
    },
    'POST /purchase-orders/' => function() use ($purchaseOrderController) {
        $purchaseOrderController->createRecord();
    },
    'PUT /purchase-orders/{id}/' => function($id) use ($purchaseOrderController) {
        $purchaseOrderController->updateRecord($id); // Pass ID directly
    },
    'DELETE /purchase-orders/{id}/' => function($id) use ($purchaseOrderController) {
        $purchaseOrderController->deleteRecord($id); // Pass ID directly
    }
];
