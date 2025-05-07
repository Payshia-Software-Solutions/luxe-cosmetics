<?php
require_once './models/WebsiteMode.php';

class WebsiteModeController
{

    private $model;

    public function __construct($pdo)
    {
        $this->model = new WebsiteMode($pdo);  // Use the correct class name
    }

    // Get the current mode from the database
    public function getCurrentMode()
    {
        $mode = $this->model->getMode();
        echo json_encode(['mode' => $mode]);
    }

    // Set a new mode
    public function setMode($newMode)
    {
        // Validate the input mode
        if (in_array($newMode, ['normal', 'maintenance', 'coming-soon'])) {
            $this->model->setMode($newMode);  // Call the method to update the mode
            echo json_encode(['message' => 'Mode updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid mode. Allowed modes are: normal, maintenance, coming-soon']);
        }
    }
}
