<?php
require_once './models/PromoCodeProduct.php'; // Include the PromoCodeProduct model

class PromoCodeProductsController
{
    private $model;

    // Constructor to initialize the PromoCodeProduct model
    public function __construct($pdo)
    {
        $this->model = new PromoCodeProduct($pdo);
    }

    // Get all promo code product records
    public function getAllRecords()
    {
        $records = $this->model->getAllPromoCodeProducts();
        echo json_encode($records);
    }

    // Get a single promo code product record by ID
    public function getRecordById($id)
    {
        $record = $this->model->getPromoCodeProductById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Promo code product not found']);
        }
    }

    public function getRecordByPromoCode($promoCode)
    {
        $record = $this->model->getPromoCodeProductByPromoCode($promoCode);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Promo code product not found']);
        }
    }

    public function getPromoCodeProductByPromoCodeActive($promoCode)
    {
        $record = $this->model->getPromoCodeProductByPromoCodeActive($promoCode);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Promo code product not found']);
        }
    }

    public function createRecord()
    {
        // Access data from the $_POST superglobal
        $promoCodeId = isset($_POST['promoCodeId']) ? $_POST['promoCodeId'] : null;
        $loggedUser  = isset($_POST['LoggedUser']) ? $_POST['LoggedUser'] : null;
        $userLevel   = isset($_POST['UserLevel']) ? $_POST['UserLevel'] : null;
        $companyId   = isset($_POST['company_id']) ? $_POST['company_id'] : null;
        $products    = isset($_POST['products']) ? json_decode($_POST['products'], true) : null;

        // Validate required fields
        if (
            $promoCodeId && $loggedUser && $userLevel && $companyId &&
            $products && is_array($products) && count($products) > 0
        ) {
            // Loop through the selected product IDs and create a record for each
            foreach ($products as $productData) {
                if (isset($productData['product_id']) && isset($productData['status'])) {
                    // Prepare the data for creating or updating the promo code product
                    $product = [
                        'promo_code' => $promoCodeId,
                        'product_id' => $productData['product_id'],
                        'status'     => $productData['status'],  // Get the status from the form data
                        'created_at' => date('Y-m-d H:i:s'), // Use current timestamp
                        'updated_at' => date('Y-m-d H:i:s'), // Use current timestamp
                    ];
                    // Insert or update the promo code product
                    $this->model->createPromoCodeProduct($product);
                }
            }
            http_response_code(201);
            echo json_encode(['status' => 'success', 'message' => 'Promo code products created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid input or no products selected']);
        }
    }




    // Delete a promo code product record by ID
    public function deleteRecord($id)
    {
        $this->model->deletePromoCodeProduct($id);
        echo json_encode(['message' => 'Promo code product deleted successfully']);
    }
}
