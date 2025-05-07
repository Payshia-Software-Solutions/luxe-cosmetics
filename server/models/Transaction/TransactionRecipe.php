<?php

class TransactionRecipe {
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fetch all recipe records
    public function getAllRecipes() {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_recipe` ORDER BY `main_product` ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single recipe by main product ID
    public function getRecipeByMainProduct($main_product) {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_recipe` WHERE `main_product` = ?");
        $stmt->execute([$main_product]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new recipe
    public function createRecipe($data) {
        $stmt = $this->pdo->prepare("INSERT INTO `transaction_recipe` (
            `main_product`, `recipe_product`, `qty`, `created_by`, `created_at`
        ) VALUES (?, ?, ?, ?, ?)");

        $stmt->execute([
            $data['main_product'],
            $data['recipe_product'],
            $data['qty'],
            $data['created_by'],
            $data['created_at']
        ]);
        return $this->pdo->lastInsertId(); // Return the ID of the newly created record
    }

    // Update an existing recipe by main product ID
    public function updateRecipe($main_product, $data) {
        $stmt = $this->pdo->prepare("UPDATE `transaction_recipe` SET 
            `recipe_product` = ?, 
            `qty` = ?, 
            `created_by` = ?, 
            `created_at` = ?
            WHERE `main_product` = ?");

        $stmt->execute([
            $data['recipe_product'],
            $data['qty'],
            $data['created_by'],
            $data['created_at'],
            $main_product
        ]);
        return $stmt->rowCount(); // Returns the number of rows affected
    }

    // Delete a recipe by main product ID
    public function deleteRecipe($main_product) {
        $stmt = $this->pdo->prepare("DELETE FROM `transaction_recipe` WHERE `main_product` = ?");
        $stmt->execute([$main_product]);
        return $stmt->rowCount(); // Returns the number of rows deleted
    }
}

?>
