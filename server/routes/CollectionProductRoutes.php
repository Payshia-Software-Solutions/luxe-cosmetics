<?php
require_once './controllers/CollectionProductController.php';

$pdo = $GLOBALS['pdo'];
$collectionProductController = new CollectionProductController($pdo);

return [
    // Get all products linked to a collection
    'GET /collection-products/collection/{collection_id}/' => function ($collection_id) use ($collectionProductController) {
        $collectionProductController->getAllByCollectionId($collection_id);
    },

    // Get specific record
    'GET /collection-products/{id}/' => function ($id) use ($collectionProductController) {
        $collectionProductController->getRecordById($id);
    },

    // Create new record
    'POST /collection-products/' => function () use ($collectionProductController) {
        $collectionProductController->createRecord();
    },

    // Update record
    'PUT /collection-products/{id}/' => function ($id) use ($collectionProductController) {
        $collectionProductController->updateRecord($id);
    },

    // Delete record
    'DELETE /collection-products/{id}/' => function ($id) use ($collectionProductController) {
        $collectionProductController->deleteRecord($id);
    }
];
