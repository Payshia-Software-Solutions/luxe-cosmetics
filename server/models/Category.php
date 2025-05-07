<?php

class Categories {
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fetch all categories
    public function getAllCategories() {
        $stmt = $this->pdo->prepare("SELECT * FROM `master_categories` WHERE `is_active` = 1 ORDER BY `pos_display` ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single category by ID
    public function getCategoryById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM `master_categories` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new category
    public function createCategory($data) {
        $stmt = $this->pdo->prepare("INSERT INTO `master_categories` (`section_id`, `department_id`, `category_name`, `is_active`, `created_at`, `created_by`, `pos_display`) 
                                     VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['section_id'],
            $data['department_id'],
            $data['category_name'],
            $data['is_active'],
            $data['created_at'],
            $data['created_by'],
            $data['pos_display']
        ]);
        return $this->pdo->lastInsertId(); // Return the ID of the newly created category
    }

    // Update an existing category
    public function updateCategory($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE `master_categories` SET 
                                     `section_id` = ?, 
                                     `department_id` = ?, 
                                     `category_name` = ?, 
                                     `is_active` = ?, 
                                     `created_by` = ?, 
                                     `pos_display` = ? 
                                     WHERE `id` = ?");
        $stmt->execute([
            $data['section_id'],
            $data['department_id'],
            $data['category_name'],
            $data['is_active'],
            $data['created_by'],
            $data['pos_display'],
            $id
        ]);
        return $stmt->rowCount(); // Returns the number of rows affected
    }

    // Delete a category by ID
    public function deleteCategory($id) {
        $stmt = $this->pdo->prepare("DELETE FROM `master_categories` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount(); // Returns the number of rows deleted
    }
}
?>
