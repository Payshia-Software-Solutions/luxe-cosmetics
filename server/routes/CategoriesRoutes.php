<?php

require_once './controllers/CategoriesController.php'; // Include the CategoriesController

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$categoriesController = new CategoriesController($pdo); // Instantiate the CategoriesController

// Define routes for categories
return [
    'GET /categories/' => function() use ($categoriesController) {
        $categoriesController->getAllRecords();
    },
    'GET /categories/{category_id}/' => function($category_id) use ($categoriesController) { // Pass category_id directly
        $categoriesController->getRecordById($category_id); // Pass category_id to the method
    },
    'POST /categories/' => function() use ($categoriesController) {
        $categoriesController->createRecord();
    },
    'PUT /categories/{category_id}/' => function($category_id) use ($categoriesController) {
        $categoriesController->updateRecord($category_id); // Pass category_id directly
    },
    'DELETE /categories/{category_id}/' => function($category_id) use ($categoriesController) {
        $categoriesController->deleteRecord($category_id); // Pass category_id directly
    }
];
