<?php

require_once './controllers/PromoCodesController.php'; // Ensure the correct case in the filename

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$promoCodesController = new PromoCodesController($pdo); // Use consistent variable naming

// Define routes for promo codes
return [
    'GET /promo_codes/' => function () use ($promoCodesController) {
        $promoCodesController->getAllRecords();
    },
    'GET /promo_codes/{promo_code_id}/' => function ($promo_code_id) use ($promoCodesController) { // Pass promo_code_id as a direct argument
        $promoCodesController->getRecordById($promo_code_id); // Pass promo_code_id directly
    },
    'GET /promo_codes/by_code/{promo_code}/' => function ($promo_code) use ($promoCodesController) {
        $promoCodesController->getRecordByCode($promo_code);  // Fetch by promo_code (code)
    },
    'POST /promo_codes/' => function () use ($promoCodesController) {
        $promoCodesController->createRecord();
    },
    'PUT /promo_codes/{promo_code_id}/' => function ($promo_code_id) use ($promoCodesController) {
        $promoCodesController->updateRecord($promo_code_id); // Pass promo_code_id directly
    },
    'DELETE /promo_codes/{promo_code_id}/' => function ($promo_code_id) use ($promoCodesController) {
        $promoCodesController->deleteRecord($promo_code_id); // Pass promo_code_id directly
    }
];
