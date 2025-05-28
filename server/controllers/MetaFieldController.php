<?php
require_once './models/MetaField.php'; // Include the MetaField model

class MetaFieldController {

    private $model;

    // Constructor to initialize the MetaField model
    public function __construct($pdo) {
        $this->model = new MetaField($pdo);
    }

    // Get all meta field records
    public function getAllRecords() {
        $records = $this->model->getAllMetaFields();  // Fetch all active meta fields
        echo json_encode($records);
    }

    // Get a single meta field record by ID
    public function getRecordById($id) {
        $record = $this->model->getMetaFieldById($id);  // Fetch meta field by ID
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Meta field not found']);
        }
    }

    // Create a new meta field record
    public function createRecord() {
        $data = json_decode(file_get_contents("php://input"), true);
        // Validate input fields
        if ($data && isset($data['name']) && isset($data['description']) && isset($data['status'])) {
            $this->model->createMetaField($data);  // Call the method to create a meta field
            http_response_code(201);
            echo json_encode(['message' => 'Meta field created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing meta field record
    public function updateRecord($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        // Validate input fields
        if ($data && isset($data['name']) && isset($data['description']) && isset($data['status'])) {
            $this->model->updateMetaField($id, $data);  // Call the method to update a meta field
            echo json_encode(['message' => 'Meta field updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a meta field record by ID
    public function deleteRecord($id) {
        $this->model->deleteMetaField($id);  // Call the method to delete a meta field
        echo json_encode(['message' => 'Meta field deleted successfully']);
    }
}
?>
