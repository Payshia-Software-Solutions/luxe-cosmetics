<?php
require_once './models/Transaction/TransactionCancellation.php'; // Include the TransactionCancellation model

class TransactionCancellationController {

    private $model;

    // Constructor to initialize the TransactionCancellation model
    public function __construct($pdo) {
        $this->model = new TransactionCancellation($pdo);
    }

    // Get all cancellation records
    public function getAllRecords() {
        $records = $this->model->getAllCancellations();  // Fetch all cancellations
        echo json_encode($records);
    }

    // Get a single cancellation record by ID
    public function getRecordById($id) {
        $record = $this->model->getCancellationById($id);  // Fetch cancellation by ID
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Cancellation not found']);
        }
    }

    // Create a new cancellation record
    public function createRecord() {
        $data = json_decode(file_get_contents("php://input"), true);
        // Validate input fields for cancellation
        if ($data && isset($data['cancellation_type']) && isset($data['ref_key']) && 
            isset($data['reason']) && isset($data['created_by']) && 
            isset($data['created_at'])) {
            
            $this->model->createCancellation($data);  // Call the method to create a cancellation
            http_response_code(201);
            echo json_encode(['message' => 'Cancellation created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing cancellation record
    public function updateRecord($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        // Validate input fields for cancellation
        if ($data && isset($data['cancellation_type']) && isset($data['ref_key']) && 
            isset($data['reason']) && isset($data['created_by'])) {
            
            $this->model->updateCancellation($id, $data);  // Call the method to update a cancellation
            echo json_encode(['message' => 'Cancellation updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a cancellation record by ID
    public function deleteRecord($id) {
        $this->model->deleteCancellation($id);  // Call the method to delete a cancellation
        echo json_encode(['message' => 'Cancellation deleted successfully']);
    }
}
?>
