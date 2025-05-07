<?php

require_once './models/Transaction/TransactionQuotation.php'; 

class TransactionQuotationController {
    private $model;

    public function __construct($pdo) {
        $this->model = new TransactionQuotation($pdo);
    }

    // Get all quotation records
    public function getAllRecords() {
        $records = $this->model->getAllQuotations();
        echo json_encode($records);
    }

    // Get a single quotation record by ID
    public function getRecordById($id) {
        $record = $this->model->getQuotationById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Quotation not found']);
        }
    }

    // Create a new quotation record
    public function createRecord() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Validate required fields
        if ($data && isset($data['quote_number']) && isset($data['quote_date']) && 
            isset($data['quote_amount']) && isset($data['grand_total']) && 
            isset($data['discount_amount']) && isset($data['discount_percentage']) && 
            isset($data['customer_code']) && isset($data['service_charge']) && 
            isset($data['close_type']) && isset($data['invoice_status']) && 
            isset($data['current_time']) && isset($data['location_id']) && 
            isset($data['created_by']) && isset($data['is_active']) && 
            isset($data['cost_value'])) {

            $data['created_at'] = date('Y-m-d H:i:s'); // Set created_at timestamp
            $this->model->createQuotation($data);
            http_response_code(201);
            echo json_encode(['message' => 'Quotation created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing quotation record
    public function updateRecord($id) {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if ($data && isset($data['quote_number']) && isset($data['quote_date']) && 
            isset($data['quote_amount']) && isset($data['grand_total']) && 
            isset($data['discount_amount']) && isset($data['discount_percentage']) && 
            isset($data['customer_code']) && isset($data['service_charge']) && 
            isset($data['close_type']) && isset($data['invoice_status']) && 
            isset($data['current_time']) && isset($data['location_id']) && 
            isset($data['created_by']) && isset($data['is_active']) && 
            isset($data['cost_value'])) {

            $this->model->updateQuotation($id, $data);
            echo json_encode(['message' => 'Quotation updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a quotation record by ID
    public function deleteRecord($id) {
        $this->model->deleteQuotation($id);
        echo json_encode(['message' => 'Quotation deleted successfully']);
    }
}
?>
