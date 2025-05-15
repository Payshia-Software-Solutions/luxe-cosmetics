<?php

use PhpOffice\PhpSpreadsheet\Calculation\Category;

require_once './controllers/MasterProductController.php'; // Include the ProductController

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$productController = new ProductController($pdo); // Instantiate the ProductController

// Define routes for products
return [
    'GET /products/' => function () use ($productController) {
        $productController->getAllRecords();
    },
    'GET /products/filter-by' => function () use ($productController) {
        // Sanitize and capture query parameters from the URL
        $category = isset($_GET['category']) ? explode(',', $_GET['category']) : null;

        // Handle multiple departments
        $department = isset($_GET['department']) ? explode(',', $_GET['department']) : null;

        // Validate and sanitize price range (if provided)
        $minPrice = isset($_GET['minPrice']) && is_numeric($_GET['minPrice']) ? (float) $_GET['minPrice'] : null;
        $maxPrice = isset($_GET['maxPrice']) && is_numeric($_GET['maxPrice']) ? (float) $_GET['maxPrice'] : null;

        // Sort (if provided)
        $sort = isset($_GET['sort']) ? $_GET['sort'] : null;
        $teaFormat = isset($_GET['teaFormat']) ? explode(',', $_GET['teaFormat']) : null;

        // Call the controller's filtered function with sanitized values
        $productController->getFilteredRecords($category, $department, $minPrice, $maxPrice, $sort, $teaFormat);
    },


    'GET /products/{product_id}/' => function ($product_id) use ($productController) { // Pass product_id directly
        $productController->getRecordById($product_id); // Pass product_id to the method
    },
    'GET /products/search/category' => function () use ($productController) {
    // Get search term from query parameter
    $searchTerm = isset($_GET['term']) ? $_GET['term'] : '';
    $productController->searchByCategoryString($searchTerm);
},
    'GET /products/get-by-slug/{slug}/' => function ($slug) use ($productController) { // Pass product_id directly
        $productController->getRecordBySlug($slug); // Pass product_slug to the method
    },
    'GET /products/get-by-department/{department}/' => function ($department) use ($productController) { // Pass product_id directly
        $productController->getRecordByDepartment($department); // Pass product_slug to the method
    },
    'GET /products/get-by-category/{category}/' => function ($category) use ($productController) { // Pass product_id directly
        $productController->getRecordByCategory($category); // Pass product_slug to the method
    },
    'GET /products/get-by-section/{section}/' => function ($section) use ($productController) { // Pass product_id directly
        $productController->getRecordBySection($section); // Pass product_slug to the method
    },
    'POST /products/' => function () use ($productController) {
        $productController->createRecord();
    },
    'PUT /products/{product_id}/' => function ($product_id) use ($productController) {
        $productController->updateRecord($product_id); // Pass product_id directly
    },
    'PUT /products/{product_id}/stock-status' => function ($product_id) use ($productController) {
        $productController->updateStockStatus($product_id); // Calls the controller function
    },
    'DELETE /products/{product_id}/' => function ($product_id) use ($productController) {
        $productController->deleteRecord($product_id); // Pass product_id directly
    },
    // New route for generating a slug if not present
    'POST /products/generate-slug/{product_id}/' => function ($product_id) use ($productController) {
        $productController->generateSlug($product_id); // Call method to create a slug
    }
];
