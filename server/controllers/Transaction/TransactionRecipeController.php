<?php

require_once './models/Transaction/TransactionRecipe.php'; // Make sure the model file is correctly included

class RecipeController {

    private $model;

    // Constructor to initialize the model with a PDO connection
    public function __construct($pdo) {
        $this->model = new TransactionRecipe($pdo);
    }

    // Get all recipe records
    public function getAllRecipes() {
        $records = $this->model->getAllRecipes();
        echo json_encode($records);
    }

    // Get a single recipe by main product ID
    public function getRecipeByMainProduct($main_product) {
        $record = $this->model->getRecipeByMainProduct($main_product);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Recipe not found']);
        }
    }

    // Create a new recipe
    public function createRecipe() {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if ($data && isset($data['main_product']) && isset($data['recipe_product']) && 
            isset($data['qty']) && isset($data['created_by'])) {

            $data['created_at'] = date('Y-m-d H:i:s'); // Set the created_at timestamp
            $this->model->createRecipe($data);
            http_response_code(201);
            echo json_encode(['message' => 'Recipe created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing recipe by main product ID
    public function updateRecipe($main_product) {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if ($data && isset($data['recipe_product']) && isset($data['qty']) && 
            isset($data['created_by'])) {

            $this->model->updateRecipe($main_product, $data);
            echo json_encode(['message' => 'Recipe updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a recipe by main product ID
    public function deleteRecipe($main_product) {
        $this->model->deleteRecipe($main_product);
        echo json_encode(['message' => 'Recipe deleted successfully']);
    }
}

?>
