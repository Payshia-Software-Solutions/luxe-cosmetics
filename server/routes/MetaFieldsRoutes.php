<?php

require_once './controllers/MetaFieldController.php'; // Include the MetaFieldController

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$metaFieldController = new MetaFieldController($pdo); // Instantiate the MetaFieldController

// Define routes for meta fields
return [
    'GET /meta-fields/' => function() use ($metaFieldController) {
        $metaFieldController->getAllRecords();
    },
    'GET /meta-fields/{id}/' => function($id) use ($metaFieldController) {
        $metaFieldController->getRecordById($id);
    },
    'POST /meta-fields/' => function() use ($metaFieldController) {
        $metaFieldController->createRecord();
    },
    'PUT /meta-fields/{id}/' => function($id) use ($metaFieldController) {
        $metaFieldController->updateRecord($id);
    },
    'DELETE /meta-fields/{id}/' => function($id) use ($metaFieldController) {
        $metaFieldController->deleteRecord($id);
    }
];
