<?php

class TransactionStockEntry {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Get all stock entries
    public function getAllStockEntries() {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_stock_entry WHERE is_active = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single stock entry by ID
    public function getStockEntryById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_stock_entry WHERE id = :id AND is_active = 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new stock entry
    public function createStockEntry($data) {
        $stmt = $this->pdo->prepare("INSERT INTO transaction_stock_entry (type, quantity, product_id, reference, location_id, created_by, created_at, is_active, ref_id) 
        VALUES (:type, :quantity, :product_id, :reference, :location_id, :created_by, :created_at, :is_active, :ref_id)");

        $stmt->execute([
            ':type' => $data['type'],
            ':quantity' => $data['quantity'],
            ':product_id' => $data['product_id'],
            ':reference' => $data['reference'],
            ':location_id' => $data['location_id'],
            ':created_by' => $data['created_by'],
            ':created_at' => $data['created_at'],
            ':is_active' => $data['is_active'],
            ':ref_id' => $data['ref_id'],
        ]);
    }

    // Update an existing stock entry
    public function updateStockEntry($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE transaction_stock_entry SET type = :type, quantity = :quantity, 
        product_id = :product_id, reference = :reference, location_id = :location_id, 
        created_by = :created_by, created_at = :created_at, is_active = :is_active, ref_id = :ref_id 
        WHERE id = :id");

        $data['id'] = $id;

        $stmt->execute([
            ':id' => $data['id'],
            ':type' => $data['type'],
            ':quantity' => $data['quantity'],
            ':product_id' => $data['product_id'],
            ':reference' => $data['reference'],
            ':location_id' => $data['location_id'],
            ':created_by' => $data['created_by'],
            ':created_at' => $data['created_at'],
            ':is_active' => $data['is_active'],
            ':ref_id' => $data['ref_id'],
        ]);
    }

    // Delete a stock entry by ID
    public function deleteStockEntry($id) {
        $stmt = $this->pdo->prepare("UPDATE transaction_stock_entry SET is_active = 0 WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
?>
