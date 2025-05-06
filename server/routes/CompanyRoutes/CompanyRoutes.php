<?php

require_once './controllers/CompanyController.php';

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$companyController = new CompanyController($pdo);

// Define routes for companies
return [
    'GET /company/' => function() use ($companyController) {
        $companyController->getAllRecords();
    },
    'GET /company/{company_id}/' => function($company_id) use ($companyController) {  // Directly access the ID
        $companyController->getRecordById($company_id);  // Use the ID directly
    },
    'POST /company/' => function() use ($companyController) {
        $companyController->createRecord();
    },
    'PUT /company/{company_id}/' => function($company_id) use ($companyController) {  // Directly access the ID
        $companyController->updateRecord($company_id);  // Use the ID directly
    },
    'DELETE /company/{company_id}/' => function($company_id) use ($companyController) {  // Directly access the ID
        $companyController->deleteRecord($company_id);  // Use the ID directly
    }
];
