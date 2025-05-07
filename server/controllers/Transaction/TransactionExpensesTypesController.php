<?php
require_once './models/Transaction/TransactionExpensesTypes.php'; // Include the TransactionExpensesTypes model

class TransactionExpensesTypesController {
    private $model;

    // Constructor to initialize the TransactionExpensesTypes model
    public function __construct($pdo) {
        $this->model = new TransactionExpensesTypes($pdo);
    }

    // Get all expense type records
    public function getAllRecords() {
        $records = $this->model->getAllTypes();  // Fetch all expense types
        echo json_encode($records);
    }

    // Get a single expense type record by ID
    public function getRecordById($id) {
        $record = $this->model->getTypeById($id);  // Fetch expense type by ID
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Expense type not found']);
        }
    }

    // Create a new expense type record
    public function createRecord() {
        $data = json_decode(file_get_contents("php://input"), true);
        // Validate input fields for expense type
        if ($data && isset($data['type']) && isset($data['is_active'])) {
            $this->model->createType($data);  // Call the method to create an expense type
            http_response_code(201);
            echo json_encode(['message' => 'Expense type created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing expense type record
    public function updateRecord($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        // Validate input fields for expense type
        if ($data && isset($data['type']) && isset($data['is_active'])) {
            $this->model->updateType($id, $data);  // Call the method to update an expense type
            echo json_encode(['message' => 'Expense type updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete an expense type record by ID
    public function deleteRecord($id) {
        $this->model->deleteType($id);  // Call the method to delete an expense type
        echo json_encode(['message' => 'Expense type deleted successfully']);
    }
}
?>
