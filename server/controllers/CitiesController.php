<?php
require_once './models/Cities.php';

class CitiesController {

    private $model;

    public function __construct($pdo) {
        $this->model = new Cities($pdo);  // Use the correct class name
    }

    // Get all city records
    public function getAllRecords() {
        $records = $this->model->getALLcities();  // Correct method name: getALLcities()
        echo json_encode($records);
    }

    // Get a single city record by ID
    public function getRecordById($id) {
        $record = $this->model->getCityById($id);  // Correct method name: getCityById()
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'City not found']);
        }
    }

    // Create a new city record
    public function createRecord() {
        $data = json_decode(file_get_contents("php://input"), true);
        // Validate input fields for city
        if ($data && isset($data['district_id']) && isset($data['name_en']) && isset($data['name_si']) && 
            isset($data['postcode']) && isset($data['latitude']) && isset($data['longitude'])) {
            $this->model->createCity($data);  // Call the method to create a city
            http_response_code(201);
            echo json_encode(['message' => 'City created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing city record
    public function updateRecord($city_id) {
        $data = json_decode(file_get_contents("php://input"), true);
        // Validate input fields for city
        if ($data && isset($data['district_id']) && isset($data['name_en']) && isset($data['name_si']) && 
            isset($data['postcode']) && isset($data['latitude']) && isset($data['longitude'])) {
            $this->model->updateCity($city_id, $data);  // Call the method to update a city
            echo json_encode(['message' => 'City updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a city record by ID
    public function deleteRecord($city_id) {
        $this->model->deleteCity($city_id);  // Call the method to delete a city
        echo json_encode(['message' => 'City deleted successfully']);
    }
}
?>
