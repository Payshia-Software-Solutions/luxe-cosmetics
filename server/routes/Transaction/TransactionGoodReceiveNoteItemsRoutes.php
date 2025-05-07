<?php

require_once './controllers/Transaction/TransactionGoodReceiveNoteItemsController.php'; // Include the controller

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$transactionGoodReceiveNoteItemsController = new TransactionGoodReceiveNoteItemsController($pdo); // Instantiate the controller

// Define routes for goods receive note items
return [
    'GET /good-receive-note-items/' => function() use ($transactionGoodReceiveNoteItemsController) {
        $transactionGoodReceiveNoteItemsController->getAllRecords(); // Fetch all items
    },
    'GET /good-receive-note-items/{item_id}/' => function($item_id) use ($transactionGoodReceiveNoteItemsController) {
        $transactionGoodReceiveNoteItemsController->getRecordById($item_id); // Fetch item by ID
    },
    'POST /good-receive-note-items/' => function() use ($transactionGoodReceiveNoteItemsController) {
        $transactionGoodReceiveNoteItemsController->createRecord(); // Create a new item
    },
    'PUT /good-receive-note-items/{item_id}/' => function($item_id) use ($transactionGoodReceiveNoteItemsController) {
        $transactionGoodReceiveNoteItemsController->updateRecord($item_id); // Update item by ID
    },
    'DELETE /good-receive-note-items/{item_id}/' => function($item_id) use ($transactionGoodReceiveNoteItemsController) {
        $transactionGoodReceiveNoteItemsController->deleteRecord($item_id); // Delete item by ID
    }
];
