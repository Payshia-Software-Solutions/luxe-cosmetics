<?php
require_once './controllers/ProductEcomValuesController.php'; // Include the ProductEcomValuesController

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$productEcomValuesController = new ProductEcomValuesController($pdo);

// Define routes for product e-commerce values
return [
    'GET /product-ecom-values/' => function () use ($productEcomValuesController) {
        $productEcomValuesController->getAllRecords();
    },
    'GET /product-ecom-values/{id}/' => function ($id) use ($productEcomValuesController) {
        $productEcomValuesController->getRecordById($id);
    },
    'GET /product-ecom-values/by-sku/{sku_barcode}/' => function ($sku_barcode) use ($productEcomValuesController) {
        $productEcomValuesController->getRecordBySkuBarcode($sku_barcode);
    },
    'POST /product-ecom-values/' => function () use ($productEcomValuesController) {
        $productEcomValuesController->createRecord();
    },
    'PUT /product-ecom-values/{id}/' => function ($id) use ($productEcomValuesController) {
        $productEcomValuesController->updateRecord($id);
    },
    'PUT /product-ecom-values/by-sku/{sku_barcode}/' => function ($sku_barcode) use ($productEcomValuesController) {
        $productEcomValuesController->updateRecordBySkuBarcode($sku_barcode);
    },
    'DELETE /product-ecom-values/{id}/' => function ($id) use ($productEcomValuesController) {
        $productEcomValuesController->deleteRecord($id);
    }
];