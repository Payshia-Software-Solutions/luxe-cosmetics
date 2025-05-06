<?php

class TransactionReceipt
{
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }



    // Generate a new REC number
    public function generateReceiptNumber($prefix = "TJ-REC")
    {
        // Count the total number of invoices
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS rec_count FROM `transaction_receipt`");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Get the count of invoices
        $rec_count = $result['rec_count'] ?? 0;

        // Increment the count to generate the next invoice number
        $new_rec_count = $rec_count + 1;

        // Generate the formatted invoice number
        return $prefix . str_pad($new_rec_count, 6, '0', STR_PAD_LEFT); // Example: INV-000001
    }


    // Fetch all transaction receipts
    public function getAllReceipts()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_receipt` ORDER BY `id` ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single transaction receipt by ID
    public function getReceiptById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_receipt` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new transaction receipt
    public function createReceipt($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO `transaction_receipt` (
            `rec_number`, `type`, `is_active`, `date`, `current_time`, `amount`, 
            `created_by`, `ref_id`, `location_id`, `customer_id`, `today_invoice`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $data['rec_number'],
            $data['type'],
            $data['is_active'],
            $data['date'],
            $data['current_time'],
            $data['amount'],
            $data['created_by'],
            $data['ref_id'],
            $data['location_id'],
            $data['customer_id'],
            $data['today_invoice']
        ]);
        return $this->pdo->lastInsertId(); // Return the ID of the newly created receipt
    }

    // Update an existing transaction receipt
    public function updateReceipt($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE `transaction_receipt` SET 
            `rec_number` = ?, 
            `type` = ?, 
            `is_active` = ?, 
            `date` = ?, 
            `current_time` = ?, 
            `amount` = ?, 
            `created_by` = ?, 
            `ref_id` = ?, 
            `location_id` = ?, 
            `customer_id` = ?, 
            `today_invoice` = ? 
            WHERE `id` = ?");

        $stmt->execute([
            $data['rec_number'],
            $data['type'],
            $data['is_active'],
            $data['date'],
            $data['current_time'],
            $data['amount'],
            $data['created_by'],
            $data['ref_id'],
            $data['location_id'],
            $data['customer_id'],
            $data['today_invoice'],
            $id
        ]);
        return $stmt->rowCount(); // Returns the number of rows affected
    }

    // Delete a transaction receipt by ID
    public function deleteReceipt($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `transaction_receipt` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount(); // Returns the number of rows deleted
    }
}
