<?php
require_once 'models/SubscriptionModel.php';

class SubscriptionController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new SubscriptionModel($pdo);  // Use the correct class name
    }


    // Handle user subscription
    public function subscribeUser()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if ($data && isset($data['name']) && isset($data['email'])) {
            if ($this->model->isEmailSubscribed($data['email'])) {
                http_response_code(409); // Conflict
                echo json_encode(['error' => 'You have already subscribed.']);
            } elseif ($this->model->createSubscription($data)) {
                http_response_code(201);
                echo json_encode(['message' => 'Subscription successful', 'code' => 'TEAJAR2025']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to create subscription']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Retrieve all subscribers
    public function getSubscribers()
    {
        $subscribers = $this->model->getAllSubscribers();
        http_response_code(200);
        echo json_encode($subscribers);
    }
}
