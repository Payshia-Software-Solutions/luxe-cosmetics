<?php

class TransactionProductionItem {
    
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fetch all production item records
    public function getAllProductionItemRecords() {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_production_items` ORDER BY `id` ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single production item record by ID
    public function getProductionItemRecordById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_production_items` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new production item record
    public function createProductionItemRecord($data) {
        $stmt = $this->pdo->prepare("INSERT INTO `transaction_production_items` (
            `product_id`, `quantity`, `cost_price`, `pn_id`, `created_at`, `created_by`, `is_active`
        ) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $data['product_id'],
            $data['quantity'],
            $data['cost_price'],
            $data['pn_id'],
            date('Y-m-d H:i:s'), // Set created_at timestamp
            $data['created_by'],
            $data['is_active']
        ]);
        return $this->pdo->lastInsertId(); // Return the ID of the newly created record
    }

    // Update an existing production item record
    public function updateProductionItemRecord($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE `transaction_production_items` SET 
            `product_id` = ?, 
            `quantity` = ?, 
            `cost_price` = ?, 
            `pn_id` = ?, 
            `created_at` = ?, 
            `created_by` = ?, 
            `is_active` = ?
            WHERE `id` = ?");

        $stmt->execute([
            $data['product_id'],
            $data['quantity'],
            $data['cost_price'],
            $data['pn_id'],
            date('Y-m-d H:i:s'), // Update created_at timestamp
            $data['created_by'],
            $data['is_active'],
            $id
        ]);
        return $stmt->rowCount(); // Return the number of rows affected
    }

    // Delete a production item record by ID
    public function deleteProductionItemRecord($id) {
        $stmt = $this->pdo->prepare("DELETE FROM `transaction_production_items` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount(); // Return the number of rows deleted
    }
}
?>
