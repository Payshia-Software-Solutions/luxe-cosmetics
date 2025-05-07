<?php
require_once './models/Transaction/TransactionGoodReceiveNote.php'; // Include the TransactionGoodReceiveNote model

class TransactionGoodReceiveNoteController {

    private $model;

    // Constructor to initialize the TransactionGoodReceiveNote model
    public function __construct($pdo) {
        $this->model = new TransactionGoodReceiveNote($pdo);
    }

    // Get all goods receive notes
    public function getAllRecords() {
        $records = $this->model->getAllNotes();  // Fetch all notes
        echo json_encode($records);
    }

    // Get a single goods receive note by ID
    public function getRecordById($id) {
        $record = $this->model->getNoteById($id);  // Fetch note by ID
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Goods Receive Note not found']);
        }
    }

    // Create a new goods receive note
    public function createRecord() {
        $data = json_decode(file_get_contents("php://input"), true);
        // Validate input fields for goods receive note
        if ($data && isset($data['grn_number']) && isset($data['location_id']) &&
            isset($data['supplier_id']) && isset($data['currency']) &&
            isset($data['tax_type']) && isset($data['sub_total']) &&
            isset($data['grand_total']) && isset($data['created_by']) &&
            isset($data['created_at']) && isset($data['is_active']) &&
            isset($data['grn_status']) && isset($data['remarks']) &&
            isset($data['payment_status']) && isset($data['po_number'])) {
            
            $this->model->createNote($data);  // Call the method to create a note
            http_response_code(201);
            echo json_encode(['message' => 'Goods Receive Note created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing goods receive note
    public function updateRecord($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        // Validate input fields for goods receive note
        if ($data && isset($data['grn_number']) && isset($data['location_id']) &&
            isset($data['supplier_id']) && isset($data['currency']) &&
            isset($data['tax_type']) && isset($data['sub_total']) &&
            isset($data['grand_total']) && isset($data['created_by']) &&
            isset($data['created_at']) && isset($data['is_active']) &&
            isset($data['grn_status']) && isset($data['remarks']) &&
            isset($data['payment_status']) && isset($data['po_number'])) {
            
            $this->model->updateNote($id, $data);  // Call the method to update a note
            echo json_encode(['message' => 'Goods Receive Note updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a goods receive note by ID
    public function deleteRecord($id) {
        $this->model->deleteNote($id);  // Call the method to delete a note
        echo json_encode(['message' => 'Goods Receive Note deleted successfully']);
    }
}
?>
