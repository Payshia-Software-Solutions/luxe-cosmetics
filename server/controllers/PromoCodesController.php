<?php
require_once './models/PromoCodes.php';

class PromoCodesController
{

    private $model;

    public function __construct($pdo)
    {
        $this->model = new PromoCodes($pdo);  // Use the correct model class
    }

    // Get all promo codes
    public function getAllRecords()
    {
        $records = $this->model->getAllPromoCodes();  // Correct method name: getAllPromoCodes()
        echo json_encode($records);
    }

    // Get a single promo code by ID
    public function getRecordById($id)
    {
        $record = $this->model->getPromoCodeById($id);  // Correct method name: getPromoCodeById()
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Promo code not found']);
        }
    }


    // Get promo code by promo code (code field)
    public function getRecordByCode($promo_code)
    {
        $record = $this->model->getPromoCodeByCode($promo_code);  // Call method to fetch using promo code
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Promo code not found']);
        }
    }

    // Create a new promo code
    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        // Validate input fields for promo code
        if (
            $data && isset($data['code']) && isset($data['discount_type']) && isset($data['discount_value']) &&
            isset($data['start_date']) && isset($data['end_date']) && isset($data['max_uses']) &&
            isset($data['min_order_value'])
        ) {

            // Set default timestamps if not provided
            $data['created_at'] = $data['created_at'] ?? date('Y-m-d H:i:s');
            $data['updated_at'] = $data['updated_at'] ?? date('Y-m-d H:i:s');

            $this->model->createPromoCode($data);  // Call the method to create a promo code
            http_response_code(201);
            echo json_encode(['message' => 'Promo code created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing promo code
    public function updateRecord($promo_code_id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        // Validate input fields for promo code
        if (
            $data && isset($data['code']) && isset($data['discount_type']) && isset($data['discount_value']) &&
            isset($data['start_date']) && isset($data['end_date']) && isset($data['max_uses']) &&
            isset($data['min_order_value'])
        ) {

            // Update timestamp
            $data['updated_at'] = date('Y-m-d H:i:s');

            $this->model->updatePromoCode($promo_code_id, $data);  // Call the method to update a promo code
            http_response_code(201);
            echo json_encode(['message' => 'Promo code updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a promo code by ID
    public function deleteRecord($promo_code_id)
    {
        $this->model->deletePromoCode($promo_code_id);  // Call the method to delete a promo code
        echo json_encode(['message' => 'Promo code deleted successfully']);
    }
}
