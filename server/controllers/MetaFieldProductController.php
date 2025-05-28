<?php
require_once './models/MetaFieldProduct.php'; // Include the MetaFieldProduct model

class MetaFieldProductController {

    private $model;

    // Constructor to initialize the MetaFieldProduct model
    public function __construct($pdo) {
        $this->model = new MetaFieldProduct($pdo);
    }

    // Get all meta field product records
    public function getAllRecords() {
        $records = $this->model->getAllMetaFieldProducts();  // Fetch all meta field products
        echo json_encode($records);
    }

    // Get a single meta field product record by ID
    public function getRecordById($id) {
        $record = $this->model->getMetaFieldProductById($id);  // Fetch meta field product by ID
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Meta field product not found']);
        }
    }

    // Get meta field product records by meta field ID
    public function getRecordsByMetaFieldId($metaFieldId) {
        $records = $this->model->getMetaFieldProductsByMetaFieldId($metaFieldId);  // Fetch by meta field ID
        echo json_encode($records);
    }

    // Get meta field product with details (joined with meta field)
    public function getRecordWithDetails($id) {
        $record = $this->model->getMetaFieldProductWithDetails($id);  // Fetch with meta field details
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Meta field product not found']);
        }
    }

    // Create a new meta field product record
    public function createRecord() {
        $data = json_decode(file_get_contents("php://input"), true);
        // Validate input fields
        if ($data && isset($data['meta_field_id']) && isset($data['value'])) {
            $insertId = $this->model->createMetaFieldProduct($data);  // Call the method to create a meta field product
            http_response_code(201);
            echo json_encode(['message' => 'Meta field product created successfully', 'id' => $insertId]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input. meta_field_id and value are required']);
        }
    }

    // Update an existing meta field product record
    public function updateRecord($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        // Validate input fields
        if ($data && isset($data['meta_field_id']) && isset($data['value'])) {
            $rowsAffected = $this->model->updateMetaFieldProduct($id, $data);  // Call the method to update a meta field product
            if ($rowsAffected > 0) {
                echo json_encode(['message' => 'Meta field product updated successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Meta field product not found or no changes made']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input. meta_field_id and value are required']);
        }
    }

    // Delete a meta field product record by ID
    public function deleteRecord($id) {
        $rowsAffected = $this->model->deleteMetaFieldProduct($id);  // Call the method to delete a meta field product
        if ($rowsAffected > 0) {
            echo json_encode(['message' => 'Meta field product deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Meta field product not found']);
        }
    }

    // Delete all meta field product records by meta field ID
    public function deleteRecordsByMetaFieldId($metaFieldId) {
        $rowsAffected = $this->model->deleteMetaFieldProductsByMetaFieldId($metaFieldId);  // Delete by meta field ID
        echo json_encode(['message' => "Deleted $rowsAffected meta field product(s) successfully"]);
    }

    // Check if a meta field product exists
    public function checkRecordExists($id) {
        $exists = $this->model->metaFieldProductExists($id);  // Check if record exists
        echo json_encode(['exists' => $exists]);
    }

    // Get count of meta field products for a specific meta field
    public function getRecordCount($metaFieldId) {
        $count = $this->model->getMetaFieldProductCount($metaFieldId);  // Get count
        echo json_encode(['count' => $count]);
    }
}
?>