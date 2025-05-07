<?php

require_once './models/Transaction/TransactionQuotationItems.php';

class TransactionQuotationItemController {

    private $model;

    public function __construct($pdo) {
        $this->model = new TransactionQuotationItem($pdo);
    }

    // Get all transaction quotation items
    public function getAllRecords() {
        $records = $this->model->getAllItems();
        echo json_encode($records);
    }

    // Get a single transaction quotation item by ID
    public function getRecordById($item_id) {
        $record = $this->model->getItemById($item_id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Transaction Quotation Item not found']);
        }
    }

    // Create a new transaction quotation item
    public function createRecord() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Validate required fields
        if ($data && isset($data['user_id']) && isset($data['product_id']) && 
            isset($data['item_price']) && isset($data['item_discount']) && 
            isset($data['quantity']) && isset($data['added_date']) && 
            isset($data['customer_id']) && isset($data['table_id']) && 
            isset($data['quote_number']) && isset($data['cost_price'])) {

            $data['is_active'] = $data['is_active'] ?? 1; // Default to 1 if not provided
            $data['hold_status'] = $data['hold_status'] ?? 0; // Default to 0 if not provided

            $this->model->createItem($data);
            http_response_code(201);
            echo json_encode(['message' => 'Transaction Quotation Item created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing transaction quotation item
    public function updateRecord($item_id) {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if ($data && isset($data['user_id']) && isset($data['product_id']) && 
            isset($data['item_price']) && isset($data['item_discount']) && 
            isset($data['quantity']) && isset($data['added_date']) && 
            isset($data['customer_id']) && isset($data['table_id']) && 
            isset($data['quote_number']) && isset($data['cost_price'])) {

            $this->model->updateItem($item_id, $data);
            echo json_encode(['message' => 'Transaction Quotation Item updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a transaction quotation item by ID
    public function deleteRecord($item_id) {
        $this->model->deleteItem($item_id);
        echo json_encode(['message' => 'Transaction Quotation Item deleted successfully']);
    }
}
?>
