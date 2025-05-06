<?php

require_once './controllers/User/UserAccountController.php'; // Include your UserAccountController

// Instantiate the controller
$pdo = $GLOBALS['pdo']; // Assuming PDO connection is stored globally
$userAccountController = new UserAccountController($pdo); // Instantiate the controller

// Define routes for user accounts
return [
    'GET /users/' => function() use ($userAccountController) {
        $userAccountController->getAllUsers(); // Get all user accounts
    },
    'GET /users/{id}/' => function($id) use ($userAccountController) {
        $userAccountController->getUserById($id); // Get a user by ID
    },
    'POST /users/' => function() use ($userAccountController) {
        $userAccountController->createUser(); // Create a new user account
    },
    'PUT /users/{id}/' => function($id) use ($userAccountController) {
        $userAccountController->updateUser($id); // Update a user account by ID
    },
    'DELETE /users/{id}/' => function($id) use ($userAccountController) {
        $userAccountController->deleteUser($id); // Delete a user account by ID
    }
];
