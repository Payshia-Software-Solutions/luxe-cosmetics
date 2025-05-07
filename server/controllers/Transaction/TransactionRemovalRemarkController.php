<?php

require_once './models/Transaction/TransactionRemovalRemark.php'; 

class TransactionRemovalRemarkController {

    private $model;

    public function __construct($pdo) {
        $this->model = new TransactionRemovalRemark($pdo);
    }

    // Get all removal remarks
    public function getAllRecords() {
        $records = $this->model->getAllRemarks();
        echo json_encode($records);
    }

    // Get a single removal remark by ID
    public function getRecordById($id) {
        $record = $this->model->getRemarkById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Remark not found']);
        }
    }

    // Create a new removal remark record
    public function createRecord() {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if ($data && isset($data['ref_id']) && isset($data['remark']) && 
            isset($data['user_id']) && isset($data['created_by']) && 
            isset($data['created_at']) && isset($data['location_id']) && 
            isset($data['product_id']) && isset($data['item_quantity'])) {

            $data['created_at'] = date('Y-m-d H:i:s'); // Set the created_at timestamp
            $this->model->createRemark($data);
            http_response_code(201);
            echo json_encode(['message' => 'Remark created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing removal remark record
    public function updateRecord($id) {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if ($data && isset($data['ref_id']) && isset($data['remark']) && 
            isset($data['user_id']) && isset($data['created_by']) && 
            isset($data['created_at']) && isset($data['location_id']) && 
            isset($data['product_id']) && isset($data['item_quantity'])) {

            $this->model->updateRemark($id, $data);
            echo json_encode(['message' => 'Remark updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a removal remark record by ID
    public function deleteRecord($id) {
        $this->model->deleteRemark($id);
        echo json_encode(['message' => 'Remark deleted successfully']);
    }
}
?>
