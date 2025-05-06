<?php

require_once './models/Company.php';

class CompanyController {

    private $model;

    public function __construct($pdo) {
        $this->model = new Company($pdo);
    }

    // Get all company records
    public function getAllRecords() {
        $records = $this->model->getAllCompanies();

        echo json_encode($records);
    }

    // Get a single company record by ID
    public function getRecordById($company_id) {
        $record = $this->model->getCompanyById($company_id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Company not found']);
        }
    }

    // Create a new company record
    public function createRecord() {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['company_name']) && isset($data['company_address']) && isset($data['company_city']) && isset($data['company_postalcode']) && isset($data['company_email']) && isset($data['company_telephone']) && isset($data['owner_name']) && isset($data['job_position'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->model->createCompany($data);
            http_response_code(201);
            echo json_encode(['message' => 'Company created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing company record
    public function updateRecord($company_id) {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['company_name']) && isset($data['company_address']) && isset($data['company_city']) && isset($data['company_postalcode']) && isset($data['company_email']) && isset($data['company_telephone']) && isset($data['owner_name']) && isset($data['job_position'])) {
            $this->model->updateCompany($company_id, $data);
            echo json_encode(['message' => 'Company updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a company record by ID
    public function deleteRecord($company_id) {
        $this->model->deleteCompany($company_id);
        echo json_encode(['message' => 'Company deleted successfully']);
    }
}
?>
