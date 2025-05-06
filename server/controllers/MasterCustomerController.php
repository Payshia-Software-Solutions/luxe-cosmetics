<?php

require_once './models/MasterCustomer.php'; // Ensure the model file is named correctly

class CustomerController {

    private $model;

    public function __construct($pdo) {
        $this->model = new Customers($pdo);
    }

    // Get all customer records
    public function getAllRecords() {
        $records = $this->model->getAllCustomers();
        echo json_encode($records);
    }

    // Get a single customer record by ID
    public function getRecordById($customer_id) {
        $record = $this->model->getCustomerById($customer_id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Customer not found']);
        }
    }

    // Create a new customer record
    public function createRecord() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Validate required fields
        if ($data && isset($data['customer_first_name']) && isset($data['customer_last_name']) && 
            isset($data['phone_number']) && isset($data['address_line1']) && 
            isset($data['city_id']) && isset($data['created_by']) && 
            isset($data['company_id']) && isset($data['location_id']) && 
            isset($data['opening_balance']) && isset($data['is_active']) && 
            isset($data['credit_limit']) && isset($data['credit_days']) && 
            isset($data['region_id']) && isset($data['route_id']) && 
            isset($data['area_id'])) {

            $data['created_at'] = date('Y-m-d H:i:s'); // Set created_at timestamp
            $this->model->createCustomer($data);
            http_response_code(201);
            echo json_encode(['message' => 'Customer created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing customer record
    public function updateRecord($customer_id) {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if ($data && isset($data['customer_first_name']) && isset($data['customer_last_name']) && 
            isset($data['phone_number']) && isset($data['address_line1']) && 
            isset($data['city_id']) && isset($data['created_by']) && 
            isset($data['company_id']) && isset($data['location_id']) && 
            isset($data['opening_balance']) && isset($data['is_active']) && 
            isset($data['credit_limit']) && isset($data['credit_days']) && 
            isset($data['region_id']) && isset($data['route_id']) && 
            isset($data['area_id'])) {

            $this->model->updateCustomer($customer_id, $data);
            echo json_encode(['message' => 'Customer updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a customer record by id
    public function deleteRecord($customer_id) {
        $this->model->deleteCustomer($customer_id);
        echo json_encode(['message' => 'Customer deleted successfully']);
    }
}
?>
