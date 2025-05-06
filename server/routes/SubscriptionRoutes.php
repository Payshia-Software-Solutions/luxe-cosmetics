<?php
require_once 'controllers/SubscriptionController.php';

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$subscriptionController = new SubscriptionController($pdo);

// Define routes for subscriptions
return [
    'POST /subscribe' => function () use ($subscriptionController) {
        $subscriptionController->subscribeUser();
    },
    'GET /subscribers' => function () use ($subscriptionController) {
        $subscriptionController->getSubscribers();
    }
];
