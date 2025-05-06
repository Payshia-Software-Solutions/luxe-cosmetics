<?php
require_once './models/ProductEcomValue.php'; // Include the ProductEcomValue model

class ProductEcomValuesController
{
    private $model;

    // Constructor to initialize the ProductEcomValue model
    public function __construct($pdo)
    {
        $this->model = new ProductEcomValue($pdo);
    }

    // Get all product e-commerce value records
    public function getAllRecords()
    {
        $records = $this->model->getAllProductEcomValues();
        echo json_encode($records);
    }

    // Get a single product e-commerce value record by ID
    public function getRecordById($id)
    {
        $record = $this->model->getProductEcomValueById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Product Ecom Value not found']);
        }
    }

    // Get a single product e-commerce value record by SKU/Barcode
    public function getRecordBySkuBarcode($skuBarcode)
    {
        $record = $this->model->getProductEcomValueBySkuBarcode($skuBarcode);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Product Ecom Value not found']);
        }
    }

    // Create a new product e-commerce value record
    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (
            $data && isset($data['use_for']) && isset($data['sku_barcode']) &&
            isset($data['Benefits']) && isset($data['How_to_do_the_Patch_test']) &&
            isset($data['Ingredients']) && isset($data['createdby'])
        ) {
            // Set the updateby to the same value as createdby if not specified
            if (!isset($data['updateby'])) {
                $data['updateby'] = $data['createdby'];
            }
            
            $id = $this->model->createProductEcomValue($data);
            http_response_code(201);
            echo json_encode([
                'message' => 'Product Ecom Value created successfully',
                'id' => $id
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing product e-commerce value record by ID
    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (
            $data && isset($data['use_for']) && isset($data['sku_barcode']) &&
            isset($data['Benefits']) && isset($data['How_to_do_the_Patch_test']) &&
            isset($data['Ingredients']) && isset($data['updateby'])
        ) {
            $result = $this->model->updateProductEcomValue($id, $data);
            
            if ($result > 0) {
                echo json_encode(['message' => 'Product Ecom Value updated successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Product Ecom Value not found or no changes made']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update a record by SKU/Barcode
    public function updateRecordBySkuBarcode($skuBarcode)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (
            $data && isset($data['use_for']) &&
            isset($data['Benefits']) && isset($data['How_to_do_the_Patch_test']) &&
            isset($data['Ingredients']) && isset($data['updateby'])
        ) {
            $result = $this->model->updateRecordBySkuBarcode($skuBarcode, $data);
            
            if ($result > 0) {
                echo json_encode(['message' => 'Product Ecom Value updated successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Product Ecom Value not found or no changes made']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a product e-commerce value record by ID
    public function deleteRecord($id)
    {
        $result = $this->model->deleteProductEcomValue($id);
        
        if ($result > 0) {
            echo json_encode(['message' => 'Product Ecom Value deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Product Ecom Value not found']);
        }
    }
}