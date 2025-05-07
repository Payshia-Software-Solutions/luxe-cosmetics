<?php

require_once './controllers/Transaction/TransactionRecipeController.php'; // Include the controller

// Instantiate the controller
$pdo = $GLOBALS['pdo'];
$recipeController = new RecipeController($pdo); // Instantiate the controller

// Define routes for the transaction_recipe table
return [
    // Route to get all recipes
    'GET /recipes/' => function() use ($recipeController) {
        $recipeController->getAllRecipes();
    },

    // Route to get a recipe by main product ID
    'GET /recipes/{main_product}/' => function($main_product) use ($recipeController) {
        $recipeController->getRecipeByMainProduct($main_product);
    },

    // Route to create a new recipe
    'POST /recipes/' => function() use ($recipeController) {
        $recipeController->createRecipe();
    },

    // Route to update a recipe by main product ID
    'PUT /recipes/{main_product}/' => function($main_product) use ($recipeController) {
        $recipeController->updateRecipe($main_product);
    },

    // Route to delete a recipe by main product ID
    'DELETE /recipes/{main_product}/' => function($main_product) use ($recipeController) {
        $recipeController->deleteRecipe($main_product);
    }
];

?>
