<?php

// Debug: Check if this file is being loaded
error_log("MetaFieldProduct routes file loaded");

// Check if controller file exists
if (!file_exists('./controllers/MetaFieldProductController.php')) {
    error_log("MetaFieldProductController.php file not found!");
}

require_once './controllers/MetaFieldProductController.php'; // Include the MetaFieldProductController

// Instantiate the controller
$pdo = $GLOBALS['pdo'];

// Debug: Check if controller can be instantiated
try {
    $metaFieldProductController = new MetaFieldProductController($pdo);
    error_log("MetaFieldProductController instantiated successfully");
} catch (Exception $e) {
    error_log("Error instantiating MetaFieldProductController: " . $e->getMessage());
}

// Define routes for meta field products (specific routes first)
return [
    'GET /meta-field-products/by-meta-field/{metaFieldId}/' => function($metaFieldId) use ($metaFieldProductController) {
        $metaFieldProductController->getRecordsByMetaFieldId($metaFieldId);
    },
    'GET /meta-field-products/count/{metaFieldId}/' => function($metaFieldId) use ($metaFieldProductController) {
        $metaFieldProductController->getRecordCount($metaFieldId);
    },
    'GET /meta-field-products/{id}/details/' => function($id) use ($metaFieldProductController) {
        $metaFieldProductController->getRecordWithDetails($id);
    },
    'GET /meta-field-products/{id}/exists/' => function($id) use ($metaFieldProductController) {
        $metaFieldProductController->checkRecordExists($id);
    },
    'GET /get/meta-field-products/' => function() use ($metaFieldProductController) {
        $metaFieldProductController->getAllRecords();
    },
    'GET /meta-field-products/{id}/' => function($id) use ($metaFieldProductController) {
        $metaFieldProductController->getRecordById($id);
    },
    'GET /meta-field-products/count/{metaFieldId}/' => function($metaFieldId) use ($metaFieldProductController) {
        $metaFieldProductController->getRecordCount($metaFieldId);
    },
    'GET /meta-field-products/{id}/exists/' => function($id) use ($metaFieldProductController) {
        $metaFieldProductController->checkRecordExists($id);
    },
    'POST /meta-field-products/' => function() use ($metaFieldProductController) {
        $metaFieldProductController->createRecord();
    },
    'PUT /meta-field-products/{id}/' => function($id) use ($metaFieldProductController) {
        $metaFieldProductController->updateRecord($id);
    },
    'DELETE /meta-field-products/{id}/' => function($id) use ($metaFieldProductController) {
        $metaFieldProductController->deleteRecord($id);
    },
    'DELETE /meta-field-products/by-meta-field/{metaFieldId}/' => function($metaFieldId) use ($metaFieldProductController) {
        $metaFieldProductController->deleteRecordsByMetaFieldId($metaFieldId);
    }
];