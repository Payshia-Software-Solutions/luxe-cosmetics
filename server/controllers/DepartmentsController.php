<?php
require_once './models/Department.php'; // Include the Department model

class DepartmentsController
{
    private $model;

    // Constructor to initialize the Department model
    public function __construct($pdo)
    {
        $this->model = new Department($pdo);
    }

    // Get all department records
    public function getAllRecords()
    {
        $records = $this->model->getAllDepartments();
        echo json_encode($records);
    }

    // Get a single department record by ID
    public function getRecordById($id)
    {
        $record = $this->model->getDepartmentById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Department not found']);
        }
    }

    // Create a new department record
    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (
            $data && isset($data['section_id']) && isset($data['department_name']) &&
            isset($data['is_active']) && isset($data['created_at']) &&
            isset($data['created_by']) && isset($data['pos_display'])
        ) {

            $this->model->createDepartment($data);
            http_response_code(201);
            echo json_encode(['message' => 'Department created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing department record
    public function updateRecord($department_id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (
            $data && isset($data['section_id']) && isset($data['department_name']) &&
            isset($data['is_active']) && isset($data['created_by']) &&
            isset($data['pos_display'])
        ) {

            $this->model->updateDepartment($department_id, $data);
            echo json_encode(['message' => 'Department updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a department record by ID
    public function deleteRecord($department_id)
    {
        $this->model->deleteDepartment($department_id);
        echo json_encode(['message' => 'Department deleted successfully']);
    }
}
