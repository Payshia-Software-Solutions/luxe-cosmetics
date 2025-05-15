<?php

class Product
{
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fetch all products
    public function getAllProducts()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `master_product` WHERE `active_status` LIKE 1 ORDER BY `product_id` ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFilteredProducts($categories = null, $departments = null, $minPrice = null, $maxPrice = null, $sortBy = null, $teaFormats = null)
    {
        // Start building the query
        $query = "SELECT * FROM `master_product` WHERE 1=1 AND  `active_status` LIKE 1"; // Default condition for flexible filters

        // Add filters dynamically based on the parameters
        $params = [];

        if ($categories && is_array($categories)) {
            // Add dynamic placeholders for multiple departments
            $placeholders = implode(',', array_fill(0, count($categories), '?'));
            $query .= " AND `section_id` IN ($placeholders)";
            $params = array_merge($params, $categories); // Add department values to params array
        }

        if ($departments && is_array($departments)) {
            // Add dynamic placeholders for multiple departments
            $placeholders = implode(',', array_fill(0, count($departments), '?'));
            $query .= " AND `department_id` IN ($placeholders)";
            $params = array_merge($params, $departments); // Add department values to params array
        }

        if ($teaFormats && is_array($teaFormats)) {
            // Add dynamic placeholders for multiple departments
            $placeholders = implode(',', array_fill(0, count($teaFormats), '?'));
            $query .= " AND `category_id` IN ($placeholders)";
            $params = array_merge($params, $teaFormats); // Add department values to params array
        }

        if ($minPrice !== null) {
            $query .= " AND `selling_price` >= :minPrice";
            $params[':minPrice'] = $minPrice;
        }

        if ($maxPrice !== null) {
            $query .= " AND `selling_price` <= :maxPrice";
            $params[':maxPrice'] = $maxPrice;
        }

        // Add sorting logic based on the sort parameter
        switch ($sortBy) {
            case 'lowToHigh':
                $query .= " ORDER BY `selling_price` ASC";
                break;
            case 'highToLow':
                $query .= " ORDER BY `selling_price` DESC";
                break;
            case 'newestFirst':
                $query .= " ORDER BY `created_at` DESC";
                break;
            case 'oldestFirst':
                $query .= " ORDER BY `created_at` ASC";
                break;
            case 'topRated':  // New sorting option for rating
                $query .= " ORDER BY `rating` DESC";
                break;
            case 'mostReviewed': // New sorting option for most reviews
                $query .= " ORDER BY `review` DESC";
                break;
            default:
                // Default sorting, e.g., by product ID
                $query .= " ORDER BY `product_id` ASC";
                break;
        }

        // Prepare the statement
        $stmt = $this->pdo->prepare($query);

        // Bind parameters dynamically
        $bindIndex = 1; // Index for positional placeholders
        foreach ($params as $key => $value) {
            // Use positional binding for department array
            if (is_int($key)) {
                $stmt->bindValue($bindIndex++, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            }
        }

        // Execute the query
        $stmt->execute();

        // Fetch and return the filtered records
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Fetch a single product by ID
    public function getProductById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `master_product` WHERE `product_id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch a single product by slug
    public function getRecordBySlug($slug)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `master_product` WHERE `slug` = ?");
        $stmt->execute([$slug]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            // Remove HTML tags from product_description
            $product['product_description'] = strip_tags($product['product_description']);
            // Remove \r, \n, and other unnecessary whitespace characters
            $product['product_description'] = preg_replace("/\r|\n/", '', $product['product_description']);
            // Trim extra spaces
            $product['product_description'] = trim($product['product_description']);
            
            // Process the new long_description field similarly if it exists
            if (isset($product['long_description'])) {
                $product['long_description'] = strip_tags($product['long_description']);
                $product['long_description'] = preg_replace("/\r|\n/", '', $product['long_description']);
                $product['long_description'] = trim($product['long_description']);
            }
            
            // Process benefits field similarly if it exists
            if (isset($product['benefits'])) {
                $product['benefits'] = strip_tags($product['benefits']);
                $product['benefits'] = preg_replace("/\r|\n/", '', $product['benefits']);
                $product['benefits'] = trim($product['benefits']);
            }
            
            // Process meta_description field similarly if it exists
            if (isset($product['meta_description'])) {
                $product['meta_description'] = strip_tags($product['meta_description']);
                $product['meta_description'] = preg_replace("/\r|\n/", '', $product['meta_description']);
                $product['meta_description'] = trim($product['meta_description']);
            }
            
            // Convert JSON fields to PHP arrays if they exist
            if (isset($product['specifications']) && !empty($product['specifications'])) {
                $product['specifications'] = json_decode($product['specifications'], true);
            }
            
            if (isset($product['reviews']) && !empty($product['reviews'])) {
                $product['reviews'] = json_decode($product['reviews'], true);
            }
        }

        return $product;
    }

    public function getRecordBySection($section)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `master_product` WHERE `section_id` = ? AND  `active_status` LIKE 1");
        $stmt->execute([$section]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single product by department ID
    public function getRecordByDepartment($department)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `master_product` WHERE `department_id` = ? AND  `active_status` LIKE 1");
        $stmt->execute([$department]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecordByCategory($category)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `master_product` WHERE `category_id` = ? AND  `active_status` LIKE 1");
        $stmt->execute([$category]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get products filtered by category string
    public function getProductsByCategory($categoryStr)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `master_product` WHERE `category` LIKE ? AND `active_status` LIKE 1");
        $stmt->execute(['%' . $categoryStr . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get top-rated products
    public function getTopRatedProducts($limit = 10)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `master_product` WHERE `active_status` LIKE 1 AND `rating` IS NOT NULL ORDER BY `rating` DESC LIMIT ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get most reviewed products
    public function getMostReviewedProducts($limit = 10)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `master_product` WHERE `active_status` LIKE 1 AND `review` > 0 ORDER BY `review` DESC LIMIT ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create a new product
    public function createProduct($data)
    {
        $fields = [
            'product_code', 'product_name', 'display_name', 'name_si', 'name_ti', 
            'print_name', 'section_id', 'department_id', 'category_id', 'brand_id', 
            'measurement', 'reorder_level', 'lead_days', 'cost_price', 'selling_price', 
            'minimum_price', 'wholesale_price', 'price_2', 'item_type', 'item_location', 
            'image_path', 'hover_image', 'created_by', 'created_at', 'active_status', 'generic_id', 
            'supplier_list', 'size_id', 'color_id', 'product_description', 'recipe_type', 
            'barcode', 'expiry_good', 'location_list', 'opening_stock', 'rating', 'review',
            'long_description', 'benefits', 'specifications', 'category', 'meta_description', 'reviews',
            'slug' // Added slug field to the list of fields
        ];
        
        // Build the SQL query dynamically
        $placeholders = array_fill(0, count($fields), '?');
        $sql = "INSERT INTO `master_product` (`" . implode('`, `', $fields) . "`) VALUES (" . implode(', ', $placeholders) . ")";
        
        $stmt = $this->pdo->prepare($sql);
        
        // Prepare JSON fields
        $specifications = isset($data['specifications']) ? json_encode($data['specifications']) : null;
        $reviews = isset($data['reviews']) ? json_encode($data['reviews']) : null;
        
        // Build the values array
        $values = [
            $data['product_code'],
            $data['product_name'],
            $data['display_name'],
            $data['name_si'] ?? null,
            $data['name_ti'] ?? null,
            $data['print_name'] ?? null,
            $data['section_id'],
            $data['department_id'],
            $data['category_id'],
            $data['brand_id'] ?? null,
            $data['measurement'],
            $data['reorder_level'],
            $data['lead_days'],
            $data['cost_price'],
            $data['selling_price'],
            $data['minimum_price'],
            $data['wholesale_price'],
            $data['price_2'] ?? null,
            $data['item_type'],
            $data['item_location'],
            $data['image_path'],
            $data['hover_image'] ?? null, // Added hover_image (with NULL default)
            $data['created_by'],
            $data['created_at'],
            $data['active_status'] ?? 1,
            $data['generic_id'] ?? null,
            $data['supplier_list'] ?? null,
            $data['size_id'] ?? null,
            $data['color_id'] ?? null,
            $data['product_description'] ?? null,
            $data['recipe_type'] ?? null,
            $data['barcode'] ?? null,
            $data['expiry_good'] ?? null,
            $data['location_list'] ?? null,
            $data['opening_stock'] ?? null,
            isset($data['rating']) ? $data['rating'] : null,
            isset($data['review']) ? $data['review'] : 0,
            isset($data['long_description']) ? $data['long_description'] : null,
            isset($data['benefits']) ? $data['benefits'] : null,
            $specifications,
            isset($data['category']) ? $data['category'] : null,
            isset($data['meta_description']) ? $data['meta_description'] : null,
            $reviews,
            isset($data['slug']) ? $data['slug'] : null // Added slug value
        ];
        
        $stmt->execute($values);
        return $this->pdo->lastInsertId(); // Return the ID of the newly created product
    }
    // Update an existing product
    public function updateProduct($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE `master_product` SET 
            `product_code` = ?, 
            `product_name` = ?, 
            `display_name` = ?, 
            `name_si` = ?, 
            `name_ti` = ?, 
            `print_name` = ?, 
            `section_id` = ?, 
            `department_id` = ?, 
            `category_id` = ?, 
            `brand_id` = ?, 
            `measurement` = ?, 
            `reorder_level` = ?, 
            `lead_days` = ?, 
            `cost_price` = ?, 
            `selling_price` = ?, 
            `minimum_price` = ?, 
            `wholesale_price` = ?, 
            `price_2` = ?, 
            `item_type` = ?, 
            `item_location` = ?, 
            `image_path` = ?,
            `hover_image` = ?, 
            `created_by` = ?, 
            `created_at` = ?, 
            `active_status` = ?, 
            `generic_id` = ?, 
            `supplier_list` = ?, 
            `size_id` = ?, 
            `color_id` = ?, 
            `product_description` = ?, 
            `recipe_type` = ?, 
            `barcode` = ?, 
            `expiry_good` = ?, 
            `location_list` = ?, 
            `opening_stock` = ?,
            `rating` = ?,
            `review` = ?,
            `long_description` = ?,
            `benefits` = ?,
            `specifications` = ?,
            `category` = ?,
            `meta_description` = ?,
            `reviews` = ?
            WHERE `product_id` = ?");

        // Prepare JSON fields
        $specifications = isset($data['specifications']) ? json_encode($data['specifications']) : null;
        $reviews = isset($data['reviews']) ? json_encode($data['reviews']) : null;

        $stmt->execute([
            $data['product_code'],
            $data['product_name'],
            $data['display_name'],
            $data['name_si'],
            $data['name_ti'],
            $data['print_name'],
            $data['section_id'],
            $data['department_id'],
            $data['category_id'],
            $data['brand_id'],
            $data['measurement'],
            $data['reorder_level'],
            $data['lead_days'],
            $data['cost_price'],
            $data['selling_price'],
            $data['minimum_price'],
            $data['wholesale_price'],
            $data['price_2'],
            $data['item_type'],
            $data['item_location'],
            $data['image_path'],
            $data['hover_image'] ?? null, // Added hover_image field
            $data['created_by'],
            $data['created_at'],
            $data['active_status'],
            $data['generic_id'],
            $data['supplier_list'],
            $data['size_id'],
            $data['color_id'],
            $data['product_description'],
            $data['recipe_type'],
            $data['barcode'],
            $data['expiry_good'],
            $data['location_list'],
            $data['opening_stock'],
            isset($data['rating']) ? $data['rating'] : null,
            isset($data['review']) ? $data['review'] : 0,
            isset($data['long_description']) ? $data['long_description'] : null,
            isset($data['benefits']) ? $data['benefits'] : null,
            $specifications,
            isset($data['category']) ? $data['category'] : null,
            isset($data['meta_description']) ? $data['meta_description'] : null,
            $reviews,
            $id
        ]);
        return $stmt->rowCount(); // Returns the number of rows affected
    }

    // Update product rating
    public function updateProductRating($id, $rating, $reviewCount = null)
    {
        // If reviewCount is provided, update both rating and review count
        if ($reviewCount !== null) {
            $stmt = $this->pdo->prepare("UPDATE `master_product` SET `rating` = ?, `review` = ? WHERE `product_id` = ?");
            $stmt->execute([$rating, $reviewCount, $id]);
        } else {
            // Otherwise, just update the rating
            $stmt = $this->pdo->prepare("UPDATE `master_product` SET `rating` = ? WHERE `product_id` = ?");
            $stmt->execute([$rating, $id]);
        }
        
        return $stmt->rowCount(); // Returns the number of rows affected
    }

    // Add a new review to the product's reviews
    public function addProductReview($id, $reviewData)
    {
        // First get the current product to access its reviews
        $product = $this->getProductById($id);
        $reviews = [];
        
        // If product exists and has reviews, decode them
        if ($product && isset($product['reviews']) && !empty($product['reviews'])) {
            $reviews = json_decode($product['reviews'], true);
            if (!is_array($reviews)) {
                $reviews = [];
            }
        }
        
        // Add the new review with timestamp
        $reviewData['timestamp'] = date('Y-m-d H:i:s');
        $reviews[] = $reviewData;
        
        // Calculate new average rating
        $totalRating = 0;
        foreach ($reviews as $review) {
            if (isset($review['rating'])) {
                $totalRating += $review['rating'];
            }
        }
        $newRating = count($reviews) > 0 ? round($totalRating / count($reviews), 2) : 0;
        
        // Update the product with new reviews and rating
        $stmt = $this->pdo->prepare("UPDATE `master_product` SET `reviews` = ?, `rating` = ?, `review` = ? WHERE `product_id` = ?");
        $stmt->execute([json_encode($reviews), $newRating, count($reviews), $id]);
        
        return $stmt->rowCount(); // Returns the number of rows affected
    }

    // Delete a product by ID
    public function deleteProduct($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `master_product` WHERE `product_id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount(); // Returns the number of rows deleted
    }

    // Function to create a unique slug for a product if not available
    public function createSlugIfNotExists($id)
    {
        // Fetch the product to check if a slug exists
        $product = $this->getProductById($id);
        if ($product && empty($product['slug'])) {
            $slug = $this->generateSlug($product['product_name']);

            // Ensure the slug is unique by appending an index if necessary
            $slug = $this->ensureUniqueSlug($slug);

            // Update the product with the generated slug
            $stmt = $this->pdo->prepare("UPDATE `master_product` SET `slug` = ? WHERE `product_id` = ?");
            $stmt->execute([$slug, $id]);

            return $slug; // Return the generated slug
        }

        return $product ? $product['slug'] : null; // Return existing slug or null if product not found
    }

    // Generate a slug from the product name
    private function generateSlug($name)
    {
        // Convert name to lowercase, replace spaces with hyphens, and remove special characters
        return preg_replace('/[^a-z0-9-]+/', '', strtolower(str_replace(' ', '-', $name)));
    }

    // Ensure the slug is unique
    private function ensureUniqueSlug($slug)
    {
        $originalSlug = $slug;
        $counter = 1;

        while ($this->isSlugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    // Check if the slug already exists in the database
    private function isSlugExists($slug)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM `master_product` WHERE `slug` = ?");
        $stmt->execute([$slug]);
        return $stmt->fetchColumn() > 0;
    }

    // Update Stock Status a product by ID
    public function changeStockStatus($statusCode, $id)
    {
        if (!is_int($statusCode) || !is_int($id)) {
            throw new InvalidArgumentException("Invalid data type for statusCode or id");
        }

        $stmt = $this->pdo->prepare("UPDATE `master_product` SET `stock_status` = ? WHERE `product_id` = ?");
        $stmt->execute([$statusCode, $id]);

        return $stmt->rowCount(); // Returns the number of rows affected
    }
    
    // Update hover image for a product
    public function updateHoverImage($id, $hoverImagePath)
    {
        $stmt = $this->pdo->prepare("UPDATE `master_product` SET `hover_image` = ? WHERE `product_id` = ?");
        $stmt->execute([$hoverImagePath, $id]);
        return $stmt->rowCount(); // Returns the number of rows affected
    }

    public function searchProductsByCategoryString($searchTerm)
{
    // Clean the search term
    $searchTerm = trim($searchTerm);
    
    // Search in the category field of the product table
    $stmt = $this->pdo->prepare("SELECT * FROM `master_product` 
                                WHERE `category` LIKE ? 
                                AND `active_status` LIKE 1
                                ORDER BY `product_id` ASC");
    $stmt->execute(['%' . $searchTerm . '%']);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}