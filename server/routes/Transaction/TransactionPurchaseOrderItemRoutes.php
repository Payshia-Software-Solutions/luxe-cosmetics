<?php

require_once './controllers/Transaction/TransactionPurchaseOrderItemController.php'; // Include the TransactionPurchaseOrderItemController

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$purchaseOrderItemController = new TransactionPurchaseOrderItemController($pdo); // Instantiate the controller

// Define routes for purchase order items
return [
    'GET /purchase-order-items/' => function() use ($purchaseOrderItemController) {
        $purchaseOrderItemController->getAllItems();
    },
    'GET /purchase-order-items/{id}/' => function($id) use ($purchaseOrderItemController) {
        $purchaseOrderItemController->getItemById($id);
    },
    'POST /purchase-order-items/' => function() use ($purchaseOrderItemController) {
        $purchaseOrderItemController->createItem();
    },
    'PUT /purchase-order-items/{id}/' => function($id) use ($purchaseOrderItemController) {
        $purchaseOrderItemController->updateItem($id);
    },
    'DELETE /purchase-order-items/{id}/' => function($id) use ($purchaseOrderItemController) {
        $purchaseOrderItemController->deleteItem($id);
    }
];
