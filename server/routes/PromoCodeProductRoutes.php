<?php
require_once './controllers/PromoCodeProductsController.php';

$pdo = $GLOBALS['pdo'];
$promoCodeProductsController = new PromoCodeProductsController($pdo);

return [
    'POST /promo-code-products/' => function () use ($promoCodeProductsController) {
        $promoCodeProductsController->createRecord();
    },
    'GET /promo-code-products/' => function () use ($promoCodeProductsController) {
        $promoCodeProductsController->getAllRecords();
    },
    'GET /promo-code-products/{id}/' => function ($id) use ($promoCodeProductsController) {
        $promoCodeProductsController->getRecordById($id);
    },
    'GET /promo-code-products/get-by-promo-code/{promo_code}/' => function ($promo_code) use ($promoCodeProductsController) {
        $promoCodeProductsController->getRecordByPromoCode($promo_code);
    },

    'GET /promo-code-products/get-by-promo-code-active/{promo_code}/' => function ($promo_code) use ($promoCodeProductsController) {
        $promoCodeProductsController->getPromoCodeProductByPromoCodeActive($promo_code);
    },

    'DELETE /promo-code-products/{id}/' => function ($id) use ($promoCodeProductsController) {
        $promoCodeProductsController->deleteRecord($id);
    }
];
