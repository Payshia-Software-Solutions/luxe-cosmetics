<?php

require_once './controllers/CitiesController.php'; // Ensure the correct case in the filename

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$citiesController = new CitiesController($pdo); // Use consistent variable naming

// Define routes for cities
return [
    'GET /cities/' => function() use ($citiesController) {
        $citiesController->getAllRecords();
    },
    'GET /cities/{city_id}/' => function($city_id) use ($citiesController) { // Pass city_id as a direct argument
        $citiesController->getRecordById($city_id); // Pass city_id directly
    },
    'POST /cities/' => function() use ($citiesController) {
        $citiesController->createRecord();
    },
    'PUT /cities/{city_id}/' => function($city_id) use ($citiesController) {
        $citiesController->updateRecord($city_id); // Pass city_id directly
    },
    'DELETE /cities/{city_id}/' => function($city_id) use ($citiesController) {
        $citiesController->deleteRecord($city_id); // Pass city_id directly
    }
];
