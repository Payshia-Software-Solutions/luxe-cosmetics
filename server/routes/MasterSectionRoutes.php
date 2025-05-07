<?php
require_once './controllers/SectionsController.php'; // Include the SectionsController

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$sectionsController = new SectionsController($pdo); // Instantiate the SectionsController

// Define routes for sections
return [
    'GET /sections/' => function () use ($sectionsController) {
        $sectionsController->getAllRecords();
    },
    'GET /sections/{section_id}/' => function ($section_id) use ($sectionsController) { // Pass section_id directly
        $sectionsController->getRecordById($section_id); // Pass section_id to the method
    },
    'POST /sections/' => function () use ($sectionsController) {
        $sectionsController->createRecord();
    },
    'PUT /sections/{section_id}/' => function ($section_id) use ($sectionsController) {
        $sectionsController->updateRecord($section_id); // Pass section_id directly
    },
    'DELETE /sections/{section_id}/' => function ($section_id) use ($sectionsController) {
        $sectionsController->deleteRecord($section_id); // Pass section_id directly
    }
];
// update Name
