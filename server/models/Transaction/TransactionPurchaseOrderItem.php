<?php

class TransactionPurchaseOrderItem {
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fetch all purchase order items
    public function getAllItems() {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_purchase_order_items` ORDER BY `id` ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single purchase order item by ID
    public function getItemById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_purchase_order_items` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new purchase order item
    public function createItem($data) {
        $stmt = $this->pdo->prepare("INSERT INTO `transaction_purchase_order_items` (
            `product_id`, `quantity`, `order_unit`, `order_rate`, `created_by`, 
            `created_at`, `is_active`, `po_number`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $data['product_id'],
            $data['quantity'],
            $data['order_unit'],
            $data['order_rate'],
            $data['created_by'],
            $data['created_at'],
            $data['is_active'],
            $data['po_number']
        ]);
        return $this->pdo->lastInsertId(); // Return the ID of the newly created item
    }

    // Update an existing purchase order item
    public function updateItem($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE `transaction_purchase_order_items` SET 
            `product_id` = ?, 
            `quantity` = ?, 
            `order_unit` = ?, 
            `order_rate` = ?, 
            `created_by` = ?, 
            `created_at` = ?, 
            `is_active` = ?, 
            `po_number` = ? 
            WHERE `id` = ?");

        $stmt->execute([
            $data['product_id'],
            $data['quantity'],
            $data['order_unit'],
            $data['order_rate'],
            $data['created_by'],
            $data['created_at'],
            $data['is_active'],
            $data['po_number'],
            $id
        ]);
        return $stmt->rowCount(); // Returns the number of rows affected
    }

    // Delete a purchase order item by ID
    public function deleteItem($id) {
        $stmt = $this->pdo->prepare("DELETE FROM `transaction_purchase_order_items` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount(); // Returns the number of rows deleted
    }
}
?>
