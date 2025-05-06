<?php

require_once './models/Transaction/TransactionInvoice.php'; // Ensure the model file is named correctly

class TransactionInvoiceController
{

    private $model;

    public function __construct($pdo)
    {
        $this->model = new TransactionInvoice($pdo);
    }

    // Get all transaction invoices
    public function getAllRecords()
    {
        $records = $this->model->getAllInvoices();
        echo json_encode($records);
    }

    // Get a single transaction invoice by ID
    public function generateInvoiceNumber($prefix)
    {
        $record = $this->model->generateInvoiceNumber($prefix);
        echo json_encode($record);
    }

    // Get a single transaction invoice by ID
    public function getRecordById($invoice_id)
    {
        $record = $this->model->getInvoiceById($invoice_id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Invoice not found']);
        }
    }

    // Create a new transaction invoice
    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if (
            $data && isset($data['invoice_number']) && isset($data['invoice_date']) &&
            isset($data['inv_amount']) && isset($data['grand_total']) &&
            isset($data['discount_amount']) && isset($data['discount_percentage']) &&
            isset($data['customer_code']) && isset($data['service_charge']) &&
            isset($data['tendered_amount']) && isset($data['close_type']) &&
            isset($data['invoice_status']) && isset($data['current_time']) &&
            isset($data['location_id']) && isset($data['table_id']) &&
            isset($data['created_by']) && isset($data['is_active']) &&
            isset($data['steward_id']) && isset($data['cost_value'])
        ) {

            $data['current_time'] = date('Y-m-d H:i:s'); // Set current timestamp
            $this->model->createInvoice($data);
            http_response_code(201);
            echo json_encode(['message' => 'Transaction invoice created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing transaction invoice
    public function updateRecord($invoice_id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if (
            $data && isset($data['invoice_number']) && isset($data['invoice_date']) &&
            isset($data['inv_amount']) && isset($data['grand_total']) &&
            isset($data['discount_amount']) && isset($data['discount_percentage']) &&
            isset($data['customer_code']) && isset($data['service_charge']) &&
            isset($data['tendered_amount']) && isset($data['close_type']) &&
            isset($data['invoice_status']) && isset($data['current_time']) &&
            isset($data['location_id']) && isset($data['table_id']) &&
            isset($data['created_by']) && isset($data['is_active']) &&
            isset($data['steward_id']) && isset($data['cost_value'])
        ) {

            $this->model->updateInvoice($invoice_id, $data);
            echo json_encode(['message' => 'Transaction invoice updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a transaction invoice by ID
    public function deleteRecord($invoice_id)
    {
        $this->model->deleteInvoice($invoice_id);
        echo json_encode(['message' => 'Transaction invoice deleted successfully']);
    }
}
