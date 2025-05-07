<?php

require_once './models/Transaction/TransactionReceipt.php'; // Include the model

class TransactionReceiptController {

    private $model;

    public function __construct($pdo) {
        $this->model = new TransactionReceipt($pdo);
    }

    // Get all transaction receipts
    public function getAllRecords() {
        $records = $this->model->getAllReceipts();
        echo json_encode($records);
    }

    // Get a single transaction receipt by ID
    public function getRecordById($receipt_id) {
        $record = $this->model->getReceiptById($receipt_id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Transaction Receipt not found']);
        }
    }

    // Create a new transaction receipt
    public function createRecord() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Validate required fields
        if ($data && isset($data['rec_number']) && isset($data['type']) && 
            isset($data['is_active']) && isset($data['date']) && 
            isset($data['current_time']) && isset($data['amount']) && 
            isset($data['created_by']) && isset($data['ref_id']) && 
            isset($data['location_id']) && isset($data['customer_id']) && 
            isset($data['today_invoice'])) {

            $this->model->createReceipt($data);
            http_response_code(201);
            echo json_encode(['message' => 'Transaction Receipt created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing transaction receipt
    public function updateRecord($receipt_id) {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if ($data && isset($data['rec_number']) && isset($data['type']) && 
            isset($data['is_active']) && isset($data['date']) && 
            isset($data['current_time']) && isset($data['amount']) && 
            isset($data['created_by']) && isset($data['ref_id']) && 
            isset($data['location_id']) && isset($data['customer_id']) && 
            isset($data['today_invoice'])) {

            $this->model->updateReceipt($receipt_id, $data);
            echo json_encode(['message' => 'Transaction Receipt updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a transaction receipt by ID
    public function deleteRecord($receipt_id) {
        $this->model->deleteReceipt($receipt_id);
        echo json_encode(['message' => 'Transaction Receipt deleted successfully']);
    }
}
?>
