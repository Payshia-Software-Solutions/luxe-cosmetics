<?php

class TransactionGoodReceiveNoteItems {
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fetch all goods receive note items
    public function getAllItems() {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_good_receive_note_items` WHERE `is_active` = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single goods receive note item by ID
    public function getItemById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_good_receive_note_items` WHERE `id` = ? AND `is_active` = 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new goods receive note item
    public function createItem($data) {
        $stmt = $this->pdo->prepare("INSERT INTO `transaction_good_receive_note_items` 
            (`product_id`, `order_unit`, `order_rate`, `created_by`, `created_at`, `is_active`, `grn_number`, `received_qty`, `po_number`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['product_id'],
            $data['order_unit'],
            $data['order_rate'],
            $data['created_by'],
            $data['created_at'],
            $data['is_active'],
            $data['grn_number'],
            $data['received_qty'],
            $data['po_number']
        ]);
        return $this->pdo->lastInsertId(); // Return the ID of the newly created item
    }

    // Update an existing goods receive note item
    public function updateItem($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE `transaction_good_receive_note_items` SET 
            `product_id` = ?, 
            `order_unit` = ?, 
            `order_rate` = ?, 
            `created_by` = ?, 
            `created_at` = ?, 
            `is_active` = ?, 
            `grn_number` = ?, 
            `received_qty` = ?, 
            `po_number` = ? 
            WHERE `id` = ?");
        $stmt->execute([
            $data['product_id'],
            $data['order_unit'],
            $data['order_rate'],
            $data['created_by'],
            $data['created_at'],
            $data['is_active'],
            $data['grn_number'],
            $data['received_qty'],
            $data['po_number'],
            $id
        ]);
        return $stmt->rowCount(); // Returns the number of rows affected
    }

    // Delete a goods receive note item by ID
    public function deleteItem($id) {
        $stmt = $this->pdo->prepare("DELETE FROM `transaction_good_receive_note_items` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount(); // Returns the number of rows deleted
    }
}
?>
