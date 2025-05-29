<?php
require_once './controllers/CollectionController.php';

$pdo = $GLOBALS['pdo'];
$collectionController = new CollectionController($pdo);

return [
    'GET /collections/' => function () use ($collectionController) {
        $collectionController->getAllRecords();
    },
    'GET /collections/{id}/' => function ($id) use ($collectionController) {
        $collectionController->getRecordById($id);
    },
    'POST /collections/' => function () use ($collectionController) {
        $collectionController->createRecord();
    },
    'PUT /collections/{id}/' => function ($id) use ($collectionController) {
        $collectionController->updateRecord($id);
    },
    'DELETE /collections/{id}/' => function ($id) use ($collectionController) {
        $collectionController->deleteRecord($id);
    }
];
