<?php

class TransactionReturnItems {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Get all return items
    public function getAllItems() {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_return_items WHERE is_active = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single return item by ID
    public function getItemById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_return_items WHERE id = :id AND is_active = 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new return item
    public function createItem($data) {
        $stmt = $this->pdo->prepare("INSERT INTO transaction_return_items (rtn_number, location_id, product_id, item_rate, item_qty, updated_at, update_by) VALUES (:rtn_number, :location_id, :product_id, :item_rate, :item_qty, :updated_at, :update_by)");
        
        // Prepare data
        $stmt->bindParam(':rtn_number', $data['rtn_number']);
        $stmt->bindParam(':location_id', $data['location_id']);
        $stmt->bindParam(':product_id', $data['product_id']);
        $stmt->bindParam(':item_rate', $data['item_rate']);
        $stmt->bindParam(':item_qty', $data['item_qty']);
        $stmt->bindParam(':updated_at', $data['updated_at']);
        $stmt->bindParam(':update_by', $data['update_by']);
        
        $stmt->execute();
    }

    // Update an existing return item
    public function updateItem($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE transaction_return_items SET rtn_number = :rtn_number, location_id = :location_id, product_id = :product_id, item_rate = :item_rate, item_qty = :item_qty, updated_at = :updated_at, update_by = :update_by WHERE id = :id");
        
        // Prepare data
        $stmt->bindParam(':rtn_number', $data['rtn_number']);
        $stmt->bindParam(':location_id', $data['location_id']);
        $stmt->bindParam(':product_id', $data['product_id']);
        $stmt->bindParam(':item_rate', $data['item_rate']);
        $stmt->bindParam(':item_qty', $data['item_qty']);
        $stmt->bindParam(':updated_at', $data['updated_at']);
        $stmt->bindParam(':update_by', $data['update_by']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        $stmt->execute();
    }

    // Delete a return item by ID
    public function deleteItem($id) {
        $stmt = $this->pdo->prepare("UPDATE transaction_return_items SET is_active = 0 WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
?>
