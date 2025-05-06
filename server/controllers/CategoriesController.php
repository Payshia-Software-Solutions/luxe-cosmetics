<?php
require_once './models/Category.php'; // Include the Categories model

class CategoriesController {

    private $model;

    // Constructor to initialize the Categories model
    public function __construct($pdo) {
        $this->model = new Categories($pdo);
    }

    // Get all category records
    public function getAllRecords() {
        $records = $this->model->getAllCategories();  // Fetch all categories
        echo json_encode($records);
    }

    // Get a single category record by ID
    public function getRecordById($id) {
        $record = $this->model->getCategoryById($id);  // Fetch category by ID
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Category not found']);
        }
    }

    // Create a new category record
    public function createRecord() {
        $data = json_decode(file_get_contents("php://input"), true);
        // Validate input fields for category
        if ($data && isset($data['section_id']) && isset($data['department_id']) && 
            isset($data['category_name']) && isset($data['is_active']) && 
            isset($data['created_by']) && isset($data['pos_display'])) {
            
            $this->model->createCategory($data);  // Call the method to create a category
            http_response_code(201);
            echo json_encode(['message' => 'Category created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing category record
    public function updateRecord($category_id) {
        $data = json_decode(file_get_contents("php://input"), true);
        // Validate input fields for category
        if ($data && isset($data['section_id']) && isset($data['department_id']) && 
            isset($data['category_name']) && isset($data['is_active']) && 
            isset($data['created_by']) && isset($data['pos_display'])) {
            
            $this->model->updateCategory($category_id, $data);  // Call the method to update a category
            echo json_encode(['message' => 'Category updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a category record by ID
    public function deleteRecord($category_id) {
        $this->model->deleteCategory($category_id);  // Call the method to delete a category
        echo json_encode(['message' => 'Category deleted successfully']);
    }
}
?>
