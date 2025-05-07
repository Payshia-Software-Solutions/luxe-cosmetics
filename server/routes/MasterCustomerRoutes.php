<?php

require_once './controllers/MasterCustomerController.php'; // Ensure the controller file name is correct

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$customerController = new CustomerController($pdo);

// Define routes for customers
return [
    'GET /customers/' => [$customerController, 'getAllRecords'], // Get all customers
    'GET /customers/{customer_id}/' => [$customerController, 'getRecordById'], // Get a customer by ID
    'POST /customers/' => [$customerController, 'createRecord'], // Create a new customer
    'PUT /customers/{customer_id}/' => [$customerController, 'updateRecord'], // Update an existing customer
    'DELETE /customers/{customer_id}/' => [$customerController, 'deleteRecord'] // Delete a customer by ID
];
