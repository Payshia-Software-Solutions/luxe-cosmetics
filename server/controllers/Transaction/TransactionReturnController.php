<?php

require_once './models/Transaction/TransactionReturn.php'; 

class TransactionReturnController {

    private $model;

    public function __construct($pdo) {
        $this->model = new TransactionReturn($pdo);
    }

    // Get all return records
    public function getAllRecords() {
        $records = $this->model->getAllReturns();
        echo json_encode($records);
    }

    // Get a single return record by ID
    public function getRecordById($id) {
        $record = $this->model->getReturnById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Return record not found']);
        }
    }

    // Create a new return record
    public function createRecord() {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if ($data && isset($data['rtn_number']) && isset($data['customer_id']) && 
            isset($data['location_id']) && isset($data['created_at']) && 
            isset($data['updated_by']) && isset($data['reason']) && 
            isset($data['ref_invoice']) && isset($data['return_amount'])) {

            $data['created_at'] = date('Y-m-d H:i:s'); // Set the created_at timestamp
            $this->model->createReturn($data);
            http_response_code(201);
            echo json_encode(['message' => 'Return record created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing return record
    public function updateRecord($id) {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if ($data && isset($data['rtn_number']) && isset($data['customer_id']) && 
            isset($data['location_id']) && isset($data['updated_by']) && 
            isset($data['reason']) && isset($data['ref_invoice']) && 
            isset($data['return_amount'])) {

            $this->model->updateReturn($id, $data);
            echo json_encode(['message' => 'Return record updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a return record by ID
    public function deleteRecord($id) {
        $this->model->deleteReturn($id);
        echo json_encode(['message' => 'Return record deleted successfully']);
    }
}
?>
