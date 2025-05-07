<?php

require_once './models/Transaction/TransactionStockEntry.php'; 

class TransactionStockEntryController {
    private $model;

    public function __construct($pdo) {
        $this->model = new TransactionStockEntry($pdo);
    }

    // Get all stock entries
    public function getAllRecords() {
        $records = $this->model->getAllStockEntries();
        echo json_encode($records);
    }

    // Get a single stock entry by ID
    public function getRecordById($id) {
        $record = $this->model->getStockEntryById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Stock entry not found']);
        }
    }

    // Create a new stock entry
    public function createRecord() {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if ($data && isset($data['type'], $data['quantity'], $data['product_id'], 
            $data['reference'], $data['location_id'], $data['created_by'], 
            $data['created_at'], $data['is_active'], $data['ref_id'])) {

            $this->model->createStockEntry($data);
            http_response_code(201);
            echo json_encode(['message' => 'Stock entry created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing stock entry
    public function updateRecord($id) {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if ($data && isset($data['type'], $data['quantity'], $data['product_id'], 
            $data['reference'], $data['location_id'], $data['created_by'], 
            $data['created_at'], $data['is_active'], $data['ref_id'])) {

            $this->model->updateStockEntry($id, $data);
            echo json_encode(['message' => 'Stock entry updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a stock entry by ID
    public function deleteRecord($id) {
        $this->model->deleteStockEntry($id);
        echo json_encode(['message' => 'Stock entry deleted successfully']);
    }
}
?>
