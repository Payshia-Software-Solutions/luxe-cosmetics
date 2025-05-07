<?php

require_once './controllers/Transaction/TransactionGoodReceiveNoteController.php'; // Include the TransactionGoodReceiveNoteController

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$transactionGoodReceiveNoteController = new TransactionGoodReceiveNoteController($pdo); // Instantiate the controller

// Define routes for goods receive notes
return [
    'GET /transaction_good_receive_note/' => function() use ($transactionGoodReceiveNoteController) {
        $transactionGoodReceiveNoteController->getAllRecords(); // Get all goods receive notes
    },
    'GET /transaction_good_receive_note/{id}/' => function($id) use ($transactionGoodReceiveNoteController) {
        $transactionGoodReceiveNoteController->getRecordById($id); // Get a specific note by ID
    },
    'POST /transaction_good_receive_note/' => function() use ($transactionGoodReceiveNoteController) {
        $transactionGoodReceiveNoteController->createRecord(); // Create a new goods receive note
    },
    'PUT /transaction_good_receive_note/{id}/' => function($id) use ($transactionGoodReceiveNoteController) {
        $transactionGoodReceiveNoteController->updateRecord($id); // Update a specific note by ID
    },
    'DELETE /transaction_good_receive_note/{id}/' => function($id) use ($transactionGoodReceiveNoteController) {
        $transactionGoodReceiveNoteController->deleteRecord($id); // Delete a specific note by ID
    }
];
