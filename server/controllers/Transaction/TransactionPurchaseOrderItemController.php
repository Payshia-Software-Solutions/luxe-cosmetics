<?php

require_once './models/Transaction/TransactionPurchaseOrderItem.php'; 

class TransactionPurchaseOrderItemController {
    private $model;

    public function __construct($pdo) {
        $this->model = new TransactionPurchaseOrderItem($pdo);
    }

    // Get all purchase order items
    public function getAllItems() {
        $items = $this->model->getAllItems();
        echo json_encode($items);
    }

    // Get a single purchase order item by ID
    public function getItemById($id) {
        $item = $this->model->getItemById($id);
        if ($item) {
            echo json_encode($item);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Item not found']);
        }
    }

    // Create a new purchase order item
    public function createItem() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Validate required fields
        if ($data && isset($data['product_id']) && isset($data['quantity']) &&
            isset($data['order_unit']) && isset($data['order_rate']) &&
            isset($data['created_by']) && isset($data['created_at']) &&
            isset($data['is_active']) && isset($data['po_number'])) {

            $this->model->createItem($data);
            http_response_code(201);
            echo json_encode(['message' => 'Purchase order item created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing purchase order item
    public function updateItem($id) {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if ($data && isset($data['product_id']) && isset($data['quantity']) &&
            isset($data['order_unit']) && isset($data['order_rate']) &&
            isset($data['created_by']) && isset($data['created_at']) &&
            isset($data['is_active']) && isset($data['po_number'])) {

            $this->model->updateItem($id, $data);
            echo json_encode(['message' => 'Purchase order item updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a purchase order item by ID
    public function deleteItem($id) {
        $this->model->deleteItem($id);
        echo json_encode(['message' => 'Purchase order item deleted successfully']);
    }
}
?>
