<?php

require_once './controllers/Transaction/TransactionProductionController.php'; 

$pdo = $GLOBALS['pdo'];
$productionController = new TransactionProductionController($pdo); 


return [
    'GET /productions/' => function() use ($productionController) {
        $productionController->getAllRecords();
    },
    'GET /productions/{id}/' => function($id) use ($productionController) { 
        $productionController->getRecordById($id);
    },
    'POST /productions/' => function() use ($productionController) {
        $productionController->createRecord();
    },
    'PUT /productions/{id}/' => function($id) use ($productionController) {
        $productionController->updateRecord($id); 
    },
    'DELETE /productions/{id}/' => function($id) use ($productionController) {
        $productionController->deleteRecord($id); 
    }
];
