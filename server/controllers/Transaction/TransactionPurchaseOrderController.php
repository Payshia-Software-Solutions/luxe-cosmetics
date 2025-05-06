<?php

require_once './models/Transaction/TransactionPurchaseOrder.php'; 

class TransactionPurchaseOrderController {
    private $model;

    // Constructor to initialize the controller with the model
    public function __construct($pdo) {
        $this->model = new TransactionPurchaseOrder($pdo);
    }

    // Get all purchase order records
    public function getAllRecords() {
        $records = $this->model->getAllPurchaseOrders();
        echo json_encode($records);
    }

    // Get a single purchase order record by ID
    public function getRecordById($id) {
        $record = $this->model->getPurchaseOrderById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Purchase order not found']);
        }
    }

    // Create a new purchase order record
    public function createRecord() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Validate required fields
        if ($data && isset($data['po_number']) && isset($data['location_id']) && 
            isset($data['supplier_id']) && isset($data['currency']) && 
            isset($data['tax_type']) && isset($data['sub_total']) && 
            isset($data['created_by']) && isset($data['created_at']) && 
            isset($data['is_active']) && isset($data['po_status']) && 
            isset($data['remarks'])) {

            $this->model->createPurchaseOrder($data);
            http_response_code(201);
            echo json_encode(['message' => 'Purchase order created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing purchase order record
    public function updateRecord($id) {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if ($data && isset($data['po_number']) && isset($data['location_id']) && 
            isset($data['supplier_id']) && isset($data['currency']) && 
            isset($data['tax_type']) && isset($data['sub_total']) && 
            isset($data['created_by']) && isset($data['created_at']) && 
            isset($data['is_active']) && isset($data['po_status']) && 
            isset($data['remarks'])) {

            $this->model->updatePurchaseOrder($id, $data);
            echo json_encode(['message' => 'Purchase order updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a purchase order record by ID
    public function deleteRecord($id) {
        $this->model->deletePurchaseOrder($id);
        echo json_encode(['message' => 'Purchase order deleted successfully']);
    }
}
?>
