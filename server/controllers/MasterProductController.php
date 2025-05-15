<?php

require_once './models/MasterProduct.php'; // Ensure the model file is named correctly

class ProductController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Product($pdo);
    }

    // Get all product records
    public function getAllRecords()
    {
        $records = $this->model->getAllProducts();
        echo json_encode($records);
    }

    // Get filtered products
    public function getFilteredRecords($category = null, $department = null, $minPrice = null, $maxPrice = null, $sortBy = null, $teaFormat = null)
    {
        // Fetch filtered products from the model
        $products = $this->model->getFilteredProducts($category, $department, $minPrice, $maxPrice, $sortBy, $teaFormat);

        // Return filtered products as JSON
        echo json_encode($products);
    }

    // Get a single product record by ID
    public function getRecordById($product_id)
    {
        $record = $this->model->getProductById($product_id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Product not found']);
        }
    }

    // Get product records by section
    public function getRecordBySection($section)
    {
        $record = $this->model->getRecordBySection($section);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Products not found']);
        }
    }

    // Get product records by department
    public function getRecordByDepartment($department)
    {
        $record = $this->model->getRecordByDepartment($department);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Products not found']);
        }
    }

    // Get product records by category ID
    public function getRecordByCategory($category)
    {
        $record = $this->model->getRecordByCategory($category);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Products not found']);
        }
    }
    
    // Get product records by category string
    public function getProductsByCategory($categoryStr)
    {
        $record = $this->model->getProductsByCategory($categoryStr);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Products not found']);
        }
    }
    
    // Get top rated products
    public function getTopRatedProducts($limit = 10)
    {
        $records = $this->model->getTopRatedProducts($limit);
        if ($records) {
            echo json_encode($records);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'No rated products found']);
        }
    }
    
    // Get most reviewed products
    public function getMostReviewedProducts($limit = 10)
    {
        $records = $this->model->getMostReviewedProducts($limit);
        if ($records) {
            echo json_encode($records);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'No reviewed products found']);
        }
    }

    // Get a product record by slug
    public function getRecordBySlug($slug)
    {
        $record = $this->model->getRecordBySlug($slug);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Product not found']);
        }
    }

    // Create a new product record with automatic slug generation
    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if (
            $data && isset($data['product_code']) && isset($data['product_name']) &&
            isset($data['display_name']) && isset($data['section_id']) &&
            isset($data['department_id']) && isset($data['category_id']) &&
            isset($data['measurement']) && isset($data['reorder_level']) &&
            isset($data['lead_days']) && isset($data['cost_price']) &&
            isset($data['selling_price']) && isset($data['minimum_price']) &&
            isset($data['wholesale_price']) && isset($data['item_type']) &&
            isset($data['item_location']) && isset($data['image_path']) &&
            isset($data['created_by'])
        ) {
            $data['created_at'] = date('Y-m-d H:i:s'); // Set created_at timestamp
            
            // Set default value for review if not provided
            if (!isset($data['review'])) {
                $data['review'] = 0;
            }
            
            // Generate a slug from the product name before saving
            if (!isset($data['slug']) || empty($data['slug'])) {
                $data['slug'] = $this->generateSlugFromName($data['product_name']);
            }
            
            $product_id = $this->model->createProduct($data);
            
            // Double check if slug was created properly, if not generate it
            if ($product_id) {
                $slug = $this->model->createSlugIfNotExists($product_id);
            }
            
            http_response_code(201);
            echo json_encode([
                'message' => 'Product created successfully', 
                'product_id' => $product_id,
                'slug' => $slug ?? $data['slug']  // Return the slug in the response
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Generate a slug from product name
    private function generateSlugFromName($name)
    {
        // Convert name to lowercase, replace spaces with hyphens, and remove special characters
        return preg_replace('/[^a-z0-9-]+/', '', strtolower(str_replace(' ', '-', $name)));
    }

    // Update an existing product record
    public function updateRecord($product_id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if (
            $data && isset($data['product_code']) && isset($data['product_name']) &&
            isset($data['display_name']) && isset($data['section_id']) &&
            isset($data['department_id']) && isset($data['category_id']) &&
            isset($data['measurement']) && isset($data['reorder_level']) &&
            isset($data['lead_days']) && isset($data['cost_price']) &&
            isset($data['selling_price']) && isset($data['minimum_price']) &&
            isset($data['wholesale_price']) && isset($data['item_type']) &&
            isset($data['item_location']) && isset($data['image_path']) &&
            isset($data['created_by'])
        ) {
            // If product name changed but slug wasn't updated, generate a new slug
            if (isset($data['product_name']) && (!isset($data['slug']) || empty($data['slug']))) {
                $data['slug'] = $this->generateSlugFromName($data['product_name']);
            }
            
            $affectedRows = $this->model->updateProduct($product_id, $data);
            
            if ($affectedRows > 0) {
                echo json_encode(['message' => 'Product updated successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Product not found or no changes made']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }
    
    // Update product rating and review count
    public function updateProductRating($product_id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Validate required fields
        if ($data && isset($data['rating'])) {
            $reviewCount = isset($data['review']) ? $data['review'] : null;
            $affectedRows = $this->model->updateProductRating($product_id, $data['rating'], $reviewCount);
            
            if ($affectedRows > 0) {
                echo json_encode(['message' => 'Product rating updated successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Product not found or no changes made']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }
    
    // Add a new review to a product
    public function addProductReview($product_id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Validate required fields for a review
        if ($data && isset($data['rating']) && isset($data['reviewer_name']) && isset($data['review_text'])) {
            $reviewData = [
                'rating' => (float)$data['rating'],
                'reviewer_name' => $data['reviewer_name'],
                'review_text' => $data['review_text'],
                'verified_purchase' => isset($data['verified_purchase']) ? (bool)$data['verified_purchase'] : false
            ];
            
            $affectedRows = $this->model->addProductReview($product_id, $reviewData);
            
            if ($affectedRows > 0) {
                echo json_encode(['message' => 'Review added successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Product not found or review could not be added']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid review data']);
        }
    }
    
    // Update product specifications
    public function updateProductSpecifications($product_id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Validate specifications data
        if ($data && isset($data['specifications']) && is_array($data['specifications'])) {
            // Get current product data
            $product = $this->model->getProductById($product_id);
            
            if (!$product) {
                http_response_code(404);
                echo json_encode(['error' => 'Product not found']);
                return;
            }
            
            // Update only the specifications field
            $product['specifications'] = $data['specifications'];
            
            $affectedRows = $this->model->updateProduct($product_id, $product);
            
            if ($affectedRows > 0) {
                echo json_encode(['message' => 'Product specifications updated successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to update product specifications']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid specifications data']);
        }
    }
    
    // Update product long description, benefits, and meta description
    public function updateProductDescriptions($product_id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        
        
        if ($data && (isset($data['long_description']) || isset($data['benefits']) || isset($data['meta_description']))) {
            // Get current product data
            $product = $this->model->getProductById($product_id);
            
            if (!$product) {
                http_response_code(404);
                echo json_encode(['error' => 'Product not found']);
                return;
            }
            
            // Update only the provided description fields
            if (isset($data['long_description'])) {
                $product['long_description'] = $data['long_description'];
            }
            
            if (isset($data['benefits'])) {
                $product['benefits'] = $data['benefits'];
            }
            
            if (isset($data['meta_description'])) {
                $product['meta_description'] = $data['meta_description'];
            }
            
            $affectedRows = $this->model->updateProduct($product_id, $product);
            
            if ($affectedRows > 0) {
                echo json_encode(['message' => 'Product descriptions updated successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to update product descriptions']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'No description fields provided']);
        }
    }

    // Delete a product record by ID
    public function deleteRecord($product_id)
    {
        $affectedRows = $this->model->deleteProduct($product_id);
        
        if ($affectedRows > 0) {
            echo json_encode(['message' => 'Product deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Product not found']);
        }
    }

    // Method to generate a slug if not present
    public function generateSlug($product_id)
    {
        $slug = $this->model->createSlugIfNotExists($product_id);
        if ($slug) {
            echo json_encode(["message" => "Slug created/updated successfully", "slug" => $slug]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Product not found or slug already exists"]);
        }
    }

    // Update stock status of a product
    public function updateStockStatus($product_id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if ($data && isset($data['stock_status'])) {
            try {
                $affectedRows = $this->model->changeStockStatus((int)$data['stock_status'], (int)$product_id);

                if ($affectedRows > 0) {
                    echo json_encode(['message' => 'Stock status updated successfully']);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Product not found or no change in stock status']);
                }
            } catch (InvalidArgumentException $e) {
                http_response_code(400);
                echo json_encode(['error' => $e->getMessage()]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }



    public function searchByCategoryString($searchTerm = '')
{
    // If search term is empty, check if it was sent via GET parameter
    if (empty($searchTerm) && isset($_GET['term'])) {
        $searchTerm = $_GET['term'];
    }
    
    // If there's still no search term, return an error
    if (empty($searchTerm)) {
        http_response_code(400);
        echo json_encode(['error' => 'Search term is required']);
        return;
    }
    
    // Call the model function to search for products
    $records = $this->model->searchProductsByCategoryString($searchTerm);
    
    // Check if any products were found
    if ($records && count($records) > 0) {
        echo json_encode([
            'success' => true,
            'count' => count($records),
            'data' => $records
        ]);
    } else {
        // Return 200 status with empty result rather than 404
        // This is common for search APIs where "no results" is not an error
        echo json_encode([
            'success' => true,
            'count' => 0,
            'data' => []
        ]);
    }
}
}