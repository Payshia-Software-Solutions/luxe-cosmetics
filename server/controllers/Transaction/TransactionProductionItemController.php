<?php

require_once './models/Transaction/TransactionProductionItem.php'; 

class TransactionProductionItemController {

    private $model;

    // Constructor to initialize the model with the PDO connection
    public function __construct($pdo) {
        $this->model = new TransactionProductionItem($pdo);
    }

    // Get all production item records
    public function getAllRecords() {
        $records = $this->model->getAllProductionItemRecords();
        echo json_encode($records);
    }

    // Get a single production item record by ID
    public function getRecordById($id) {
        $record = $this->model->getProductionItemRecordById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Production item not found']);
        }
    }

    // Create a new production item record
    public function createRecord() {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if ($data && isset($data['product_id']) && isset($data['quantity']) && 
            isset($data['cost_price']) && isset($data['pn_id']) && 
            isset($data['created_by'])) {

            $data['is_active'] = isset($data['is_active']) ? $data['is_active'] : 1; // Default active status
            $this->model->createProductionItemRecord($data);
            http_response_code(201);
            echo json_encode(['message' => 'Production item created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing production item record
    public function updateRecord($id) {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if ($data && isset($data['product_id']) && isset($data['quantity']) && 
            isset($data['cost_price']) && isset($data['pn_id']) && 
            isset($data['created_by'])) {

            $this->model->updateProductionItemRecord($id, $data);
            echo json_encode(['message' => 'Production item updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a production item record by ID
    public function deleteRecord($id) {
        $this->model->deleteProductionItemRecord($id);
        echo json_encode(['message' => 'Production item deleted successfully']);
    }
}
?>
