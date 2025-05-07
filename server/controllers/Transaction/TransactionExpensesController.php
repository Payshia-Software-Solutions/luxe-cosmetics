<?php
require_once './models/Transaction/TransactionExpenses.php'; // Include the TransactionExpenses model

class TransactionExpensesController {

    private $model;

    // Constructor to initialize the TransactionExpenses model
    public function __construct($pdo) {
        $this->model = new TransactionExpenses($pdo);
    }

    // Get all expense records
    public function getAllRecords() {
        $records = $this->model->getAllExpenses();  // Fetch all expenses
        echo json_encode($records);
    }

    // Get a single expense record by ID
    public function getRecordById($id) {
        $record = $this->model->getExpenseById($id);  // Fetch expense by ID
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Expense not found']);
        }
    }

    // Create a new expense record
    public function createRecord() {
        $data = json_decode(file_get_contents("php://input"), true);
        // Validate input fields for the expense
        if ($data && isset($data['expense_id']) && isset($data['ex_type']) && 
            isset($data['ex_description']) && isset($data['amount']) && 
            isset($data['updated_by']) && isset($data['updated_at']) && 
            isset($data['location_id']) && isset($data['is_active'])) {
            
            $this->model->createExpense($data);  // Call the method to create an expense
            http_response_code(201);
            echo json_encode(['message' => 'Expense created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing expense record
    public function updateRecord($expense_id) {
        $data = json_decode(file_get_contents("php://input"), true);
        // Validate input fields for the expense
        if ($data && isset($data['expense_id']) && isset($data['ex_type']) && 
            isset($data['ex_description']) && isset($data['amount']) && 
            isset($data['updated_by']) && isset($data['updated_at']) && 
            isset($data['location_id']) && isset($data['is_active'])) {
            
            $this->model->updateExpense($expense_id, $data);  // Call the method to update an expense
            echo json_encode(['message' => 'Expense updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete an expense record by ID
    public function deleteRecord($expense_id) {
        $this->model->deleteExpense($expense_id);  // Call the method to delete an expense
        echo json_encode(['message' => 'Expense deleted successfully']);
    }
}
?>
