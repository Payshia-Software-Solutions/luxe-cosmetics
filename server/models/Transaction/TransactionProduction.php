<?php

class TransactionProduction {
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fetch all production records
    public function getAllProductionRecords() {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_production` ORDER BY `id` ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single production record by ID
    public function getProductionRecordById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_production` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new production record
    public function createProductionRecord($data) {
        $stmt = $this->pdo->prepare("INSERT INTO `transaction_production` (
            `production_cost`, `location_id`, `created_by`, `created_at`, `remark`, `production_date`, `pn_number`, `is_active`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $data['production_cost'],
            $data['location_id'],
            $data['created_by'],
            $data['created_at'],  // Make sure to pass the correct timestamp
            $data['remark'],
            $data['production_date'],  // Ensure this is in 'YYYY-MM-DD' format
            $data['pn_number'],
            $data['is_active'] ?? 1  // Default to active (1) if not provided
        ]);
        return $this->pdo->lastInsertId();  // Return the ID of the newly created record
    }

    // Update an existing production record
    public function updateProductionRecord($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE `transaction_production` SET 
            `production_cost` = ?, 
            `location_id` = ?, 
            `created_by` = ?, 
            `created_at` = ?, 
            `remark` = ?, 
            `production_date` = ?, 
            `pn_number` = ?, 
            `is_active` = ?
            WHERE `id` = ?");

        $stmt->execute([
            $data['production_cost'],
            $data['location_id'],
            $data['created_by'],
            $data['created_at'],
            $data['remark'],
            $data['production_date'],
            $data['pn_number'],
            $data['is_active'] ?? 1,  // Default to 1 if not provided
            $id
        ]);
        return $stmt->rowCount();  // Return the number of rows affected
    }

    // Delete a production record by ID
    public function deleteProductionRecord($id) {
        $stmt = $this->pdo->prepare("DELETE FROM `transaction_production` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();  // Return the number of rows deleted
    }
}
?>
