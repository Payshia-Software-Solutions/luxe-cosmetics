<?php

require_once './models/Transaction/TransactionRefund.php'; // 

class TransactionRefundController {
    private $model;

    public function __construct($pdo) {
        $this->model = new TransactionRefund($pdo); // Initialize the model with PDO
    }

    // Fetch all refund records
    public function getAllRecords() {
        $records = $this->model->getAllRefunds();
        echo json_encode($records);
    }

    // Fetch a single refund record by ID
    public function getRecordById($id) {
        $record = $this->model->getRefundById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Refund not found']);
        }
    }

    // Create a new refund record
    public function createRecord() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Validate required fields
        if ($data && isset($data['refund_id']) && isset($data['rtn_number']) && 
            isset($data['refund_amount']) && isset($data['refund_datetime']) && 
            isset($data['is_active']) && isset($data['update_by']) && 
            isset($data['customer_id']) && isset($data['rtn_location']) && 
            isset($data['current_location'])) {

            $data['refund_datetime'] = date('Y-m-d H:i:s'); // Set refund_datetime
            $this->model->createRefund($data);
            http_response_code(201);
            echo json_encode(['message' => 'Refund created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing refund record
    public function updateRecord($id) {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if ($data && isset($data['refund_id']) && isset($data['rtn_number']) && 
            isset($data['refund_amount']) && isset($data['refund_datetime']) && 
            isset($data['is_active']) && isset($data['update_by']) && 
            isset($data['customer_id']) && isset($data['rtn_location']) && 
            isset($data['current_location'])) {

            $this->model->updateRefund($id, $data);
            echo json_encode(['message' => 'Refund updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a refund record by ID
    public function deleteRecord($id) {
        $this->model->deleteRefund($id);
        echo json_encode(['message' => 'Refund deleted successfully']);
    }
}

?>
