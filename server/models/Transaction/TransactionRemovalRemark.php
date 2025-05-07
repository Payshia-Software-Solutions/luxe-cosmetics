<?php

class TransactionRemovalRemark {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Get all records
    public function getAllRemarks() {
        $stmt = $this->pdo->query("SELECT * FROM transaction_removal_remark");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a specific record by ID
    public function getRemarkById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_removal_remark WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new record
    public function createRemark($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO transaction_removal_remark 
            (ref_id, remark, user_id, created_by, created_at, location_id, product_id, item_quantity) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['ref_id'], 
            $data['remark'], 
            $data['user_id'], 
            $data['created_by'], 
            $data['created_at'], 
            $data['location_id'], 
            $data['product_id'], 
            $data['item_quantity']
        ]);
    }

    // Update an existing record
    public function updateRemark($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE transaction_removal_remark 
            SET ref_id = ?, remark = ?, user_id = ?, created_by = ?, created_at = ?, 
                location_id = ?, product_id = ?, item_quantity = ? 
            WHERE id = ?
        ");
        
        return $stmt->execute([
            $data['ref_id'], 
            $data['remark'], 
            $data['user_id'], 
            $data['created_by'], 
            $data['created_at'], 
            $data['location_id'], 
            $data['product_id'], 
            $data['item_quantity'], 
            $id
        ]);
    }

    // Delete a record by ID
    public function deleteRemark($id) {
        $stmt = $this->pdo->prepare("DELETE FROM transaction_removal_remark WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
