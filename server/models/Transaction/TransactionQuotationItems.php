<?php

class TransactionQuotationItem {
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fetch all transaction quotation items
    public function getAllItems() {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_quotation_items` ORDER BY `id` ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single transaction quotation item by ID
    public function getItemById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_quotation_items` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new transaction quotation item
    public function createItem($data) {
        $stmt = $this->pdo->prepare("INSERT INTO `transaction_quotation_items` (
            `user_id`, `product_id`, `item_price`, `item_discount`, `quantity`, 
            `added_date`, `is_active`, `customer_id`, `hold_status`, `table_id`, 
            `quote_number`, `cost_price`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $data['user_id'],
            $data['product_id'],
            $data['item_price'],
            $data['item_discount'],
            $data['quantity'],
            $data['added_date'],
            $data['is_active'],
            $data['customer_id'],
            $data['hold_status'],
            $data['table_id'],
            $data['quote_number'],
            $data['cost_price']
        ]);
        return $this->pdo->lastInsertId(); // Return the ID of the newly created item
    }

    // Update an existing transaction quotation item
    public function updateItem($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE `transaction_quotation_items` SET 
            `user_id` = ?, 
            `product_id` = ?, 
            `item_price` = ?, 
            `item_discount` = ?, 
            `quantity` = ?, 
            `added_date` = ?, 
            `is_active` = ?, 
            `customer_id` = ?, 
            `hold_status` = ?, 
            `table_id` = ?, 
            `quote_number` = ?, 
            `cost_price` = ? 
            WHERE `id` = ?");

        $stmt->execute([
            $data['user_id'],
            $data['product_id'],
            $data['item_price'],
            $data['item_discount'],
            $data['quantity'],
            $data['added_date'],
            $data['is_active'],
            $data['customer_id'],
            $data['hold_status'],
            $data['table_id'],
            $data['quote_number'],
            $data['cost_price'],
            $id
        ]);
        return $stmt->rowCount(); // Returns the number of rows affected
    }

    // Delete a transaction quotation item by ID
    public function deleteItem($id) {
        $stmt = $this->pdo->prepare("DELETE FROM `transaction_quotation_items` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount(); // Returns the number of rows deleted
    }
}
?>
