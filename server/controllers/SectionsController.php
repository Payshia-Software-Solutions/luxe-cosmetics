<?php
require_once './models/Section.php'; // Include the Sections model

class SectionsController
{

    private $model;

    // Constructor to initialize the Sections model
    public function __construct($pdo)
    {
        $this->model = new Sections($pdo);
    }

    // Get all section records
    public function getAllRecords()
    {
        $records = $this->model->getAllSections();  // Fetch all sections
        echo json_encode($records);
    }

    // Get a single section record by ID
    public function getRecordById($id)
    {
        $record = $this->model->getSectionById($id);  // Fetch section by ID
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Section not found']);
        }
    }

    // Create a new section record
    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        // Validate input fields for section
        if (
            $data && isset($data['section_name']) && isset($data['is_active']) &&
            isset($data['created_at']) && isset($data['created_by']) &&
            isset($data['pos_display'])
        ) {

            $this->model->createSection($data);  // Call the method to create a section
            http_response_code(201);
            echo json_encode(['message' => 'Section created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing section record
    public function updateRecord($section_id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        // Validate input fields for section
        if (
            $data && isset($data['section_name']) && isset($data['is_active']) &&
            isset($data['created_by']) && isset($data['pos_display'])
        ) {

            $this->model->updateSection($section_id, $data);  // Call the method to update a section
            echo json_encode(['message' => 'Section updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a section record by ID
    public function deleteRecord($section_id)
    {
        $this->model->deleteSection($section_id);  // Call the method to delete a section
        echo json_encode(['message' => 'Section deleted successfully']);
    }
}
