<?php
require_once './controllers/DepartmentsController.php'; // Include the DepartmentsController

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$departmentsController = new DepartmentsController($pdo);

// Define routes for departments
return [
    'GET /departments/' => function () use ($departmentsController) {
        $departmentsController->getAllRecords();
    },
    'GET /departments/{department_id}/' => function ($department_id) use ($departmentsController) {
        $departmentsController->getRecordById($department_id);
    },
    'POST /departments/' => function () use ($departmentsController) {
        $departmentsController->createRecord();
    },
    'PUT /departments/{department_id}/' => function ($department_id) use ($departmentsController) {
        $departmentsController->updateRecord($department_id);
    },
    'DELETE /departments/{department_id}/' => function ($department_id) use ($departmentsController) {
        $departmentsController->deleteRecord($department_id);
    }
];
