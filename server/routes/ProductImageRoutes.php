<?php

require_once './controllers/MasterProductImagesController.php';

$pdo = $GLOBALS['pdo'];  // Assume $pdo is globally available
$masterProductImagesController = new MasterProductImagesController($pdo);

return [
    'GET /product-images/' => function () use ($masterProductImagesController) {
        $masterProductImagesController->getAllImages();
    },
    'GET /product-images/{id}/' => function ($id) use ($masterProductImagesController) {
        $masterProductImagesController->getImageById($id);
    },
    'GET /product-images/get-by-product/{id}' => function ($id) use ($masterProductImagesController) {
        $masterProductImagesController->getImageByProductId($id);
    },
    'GET /product-images/get-by-product/{id}/admin' => function ($id) use ($masterProductImagesController) {
        $masterProductImagesController->getImageByProductIdAdmin($id);
    },

    'POST /product-images/' => function () use ($masterProductImagesController) {
        $masterProductImagesController->createImageNew();
    },
    'PUT /product-images/{id}/' => function ($id) use ($masterProductImagesController) {
        $masterProductImagesController->updateImage($id);
    },
    'PUT /product-images/change-status/{id}' => function ($id) use ($masterProductImagesController) {
        $masterProductImagesController->changeImageStatus($id);
    },
    'DELETE /product-images/{id}/' => function ($id) use ($masterProductImagesController) {
        $masterProductImagesController->deleteImage($id);
    }
];
