<?php

require_once './models/Transaction/TransactionProduction.php';

class TransactionProductionController {
    
    private $model;

    public function __construct($pdo) {
        $this->model = new TransactionProduction($pdo);
    }

    // Get all production records
    public function getAllRecords() {
        $records = $this->model->getAllProductionRecords();
        echo json_encode($records);
    }

    // Get a single production record by ID
    public function getRecordById($id) {
        $record = $this->model->getProductionRecordById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Production record not found']);
        }
    }

    // Create a new production record
    public function createRecord() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Validate required fields
        if ($data && isset($data['production_cost']) && isset($data['location_id']) &&
            isset($data['created_by']) && isset($data['remark']) &&
            isset($data['production_date']) && isset($data['pn_number'])) {

            $data['created_at'] = date('Y-m-d H:i:s'); // Set created_at timestamp
            $this->model->createProductionRecord($data);
            http_response_code(201);
            echo json_encode(['message' => 'Production record created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing production record
    public function updateRecord($id) {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if ($data && isset($data['production_cost']) && isset($data['location_id']) &&
            isset($data['created_by']) && isset($data['remark']) &&
            isset($data['production_date']) && isset($data['pn_number'])) {

            $this->model->updateProductionRecord($id, $data);
            echo json_encode(['message' => 'Production record updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a production record by ID
    public function deleteRecord($id) {
        $this->model->deleteProductionRecord($id);
        echo json_encode(['message' => 'Production record deleted successfully']);
    }
}
?>
