<?php

require_once './models/Transaction/TransactionReturnItems.php'; 

class TransactionReturnItemsController {

    private $model;

    public function __construct($pdo) {
        $this->model = new TransactionReturnItems($pdo);
    }

    // Get all return items
    public function getAllRecords() {
        $records = $this->model->getAllItems();
        echo json_encode($records);
    }

    // Get a single return item by ID
    public function getRecordById($id) {
        $record = $this->model->getItemById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Item not found']);
        }
    }

    // Create a new return item
    public function createRecord() {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if ($data && isset($data['rtn_number']) && isset($data['location_id']) && 
            isset($data['product_id']) && isset($data['item_rate']) && 
            isset($data['item_qty']) && isset($data['updated_at']) && 
            isset($data['update_by'])) {

            $this->model->createItem($data);
            http_response_code(201);
            echo json_encode(['message' => 'Item created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing return item
    public function updateRecord($id) {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if ($data && isset($data['rtn_number']) && isset($data['location_id']) && 
            isset($data['product_id']) && isset($data['item_rate']) && 
            isset($data['item_qty']) && isset($data['updated_at']) && 
            isset($data['update_by'])) {

            $this->model->updateItem($id, $data);
            echo json_encode(['message' => 'Item updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a return item by ID
    public function deleteRecord($id) {
        $this->model->deleteItem($id);
        echo json_encode(['message' => 'Item deleted successfully']);
    }
}
?>
