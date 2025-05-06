<?php
require_once './models/Transaction/TransactionGoodReceiveNoteItems.php'; // Include the model

class TransactionGoodReceiveNoteItemsController {
    private $model;

    // Constructor to initialize the TransactionGoodReceiveNoteItems model
    public function __construct($pdo) {
        $this->model = new TransactionGoodReceiveNoteItems($pdo);
    }

    // Get all goods receive note items
    public function getAllRecords() {
        $records = $this->model->getAllItems(); // Fetch all items
        echo json_encode($records); // Return as JSON
    }

    // Get a single goods receive note item by ID
    public function getRecordById($id) {
        $record = $this->model->getItemById($id); // Fetch item by ID
        if ($record) {
            echo json_encode($record); // Return as JSON
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Item not found']);
        }
    }

    // Create a new goods receive note item
    public function createRecord() {
        $data = json_decode(file_get_contents("php://input"), true);
        // Validate input fields
        if ($data && isset($data['product_id']) && isset($data['order_unit']) && 
            isset($data['order_rate']) && isset($data['created_by']) && 
            isset($data['created_at']) && isset($data['is_active']) && 
            isset($data['grn_number']) && isset($data['received_qty']) && 
            isset($data['po_number'])) {
            
            $this->model->createItem($data); // Call the method to create an item
            http_response_code(201);
            echo json_encode(['message' => 'Item created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing goods receive note item
    public function updateRecord($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        // Validate input fields
        if ($data && isset($data['product_id']) && isset($data['order_unit']) && 
            isset($data['order_rate']) && isset($data['created_by']) && 
            isset($data['created_at']) && isset($data['is_active']) && 
            isset($data['grn_number']) && isset($data['received_qty']) && 
            isset($data['po_number'])) {
            
            $this->model->updateItem($id, $data); // Call the method to update an item
            echo json_encode(['message' => 'Item updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a goods receive note item by ID
    public function deleteRecord($id) {
        $this->model->deleteItem($id); // Call the method to delete an item
        echo json_encode(['message' => 'Item deleted successfully']);
    }
}
?>
