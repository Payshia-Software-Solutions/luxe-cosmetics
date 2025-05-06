<?php
require_once './models/TransactionInvoiceAddress.php';

class TransactionInvoiceAddressController
{

    private $model;

    public function __construct($pdo)
    {
        $this->model = new TransactionInvoiceAddress($pdo);  // Use the correct model class name
    }

    // Get all address records
    public function getAllRecords()
    {
        $records = $this->model->getAllAddresses();  // Correct method name: getAllAddresses()
        echo json_encode($records);
    }

    // Get a single address record by ID
    public function getRecordById($id)
    {
        $record = $this->model->getAddressById($id);  // Correct method name: getAddressById()
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Address not found']);
        }
    }

    public function getRecordsByInvoice($id)
    {
        $record = $this->model->getRecordsByInvoice($id);  // Correct method name: getAddressById()
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Address not found']);
        }
    }

    // Create a new address record
    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        // Validate input fields for address
        if (
            $data && isset($data['user_id']) && isset($data['order_id']) && isset($data['address_type']) &&
            isset($data['first_name']) && isset($data['last_name']) && isset($data['phone']) &&
            isset($data['address_line1']) && isset($data['city']) && isset($data['state']) &&
            isset($data['postal_code']) && isset($data['country'])
        ) {

            // Set default timestamps if not provided
            $data['created_at'] = $data['created_at'] ?? date('Y-m-d H:i:s');
            $data['updated_at'] = $data['updated_at'] ?? date('Y-m-d H:i:s');

            $this->model->createAddress($data);  // Call the method to create an address
            http_response_code(201);
            echo json_encode(['message' => 'Address created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing address record
    public function updateRecord($address_id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        // Validate input fields for address
        if (
            $data && isset($data['user_id']) && isset($data['order_id']) && isset($data['address_type']) &&
            isset($data['first_name']) && isset($data['last_name']) && isset($data['phone']) &&
            isset($data['address_line1']) && isset($data['city']) && isset($data['state']) &&
            isset($data['postal_code']) && isset($data['country'])
        ) {

            // Update timestamp
            $data['updated_at'] = date('Y-m-d H:i:s');

            $this->model->updateAddress($address_id, $data);  // Call the method to update an address
            echo json_encode(['message' => 'Address updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete an address record by ID
    public function deleteRecord($address_id)
    {
        $this->model->deleteAddress($address_id);  // Call the method to delete an address
        echo json_encode(['message' => 'Address deleted successfully']);
    }
}
