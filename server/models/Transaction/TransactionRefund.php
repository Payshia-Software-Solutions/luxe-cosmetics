<?php

class TransactionRefund {
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fetch all refund records
    public function getAllRefunds() {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_refund` ORDER BY `id` ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single refund record by ID
    public function getRefundById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_refund` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new refund record
    public function createRefund($data) {
        $stmt = $this->pdo->prepare("INSERT INTO `transaction_refund` (
            `refund_id`, `rtn_number`, `refund_amount`, `refund_datetime`, `is_active`, 
            `update_by`, `customer_id`, `rtn_location`, `current_location`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $data['refund_id'],
            $data['rtn_number'],
            $data['refund_amount'],
            $data['refund_datetime'],
            $data['is_active'],
            $data['update_by'],
            $data['customer_id'],
            $data['rtn_location'],
            $data['current_location']
        ]);

        return $this->pdo->lastInsertId(); // Return the ID of the newly created refund
    }

    // Update an existing refund record
    public function updateRefund($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE `transaction_refund` SET 
            `refund_id` = ?, 
            `rtn_number` = ?, 
            `refund_amount` = ?, 
            `refund_datetime` = ?, 
            `is_active` = ?, 
            `update_by` = ?, 
            `customer_id` = ?, 
            `rtn_location` = ?, 
            `current_location` = ? 
            WHERE `id` = ?");
        
        $stmt->execute([
            $data['refund_id'],
            $data['rtn_number'],
            $data['refund_amount'],
            $data['refund_datetime'],
            $data['is_active'],
            $data['update_by'],
            $data['customer_id'],
            $data['rtn_location'],
            $data['current_location'],
            $id
        ]);

        return $stmt->rowCount(); // Returns the number of rows affected
    }

    // Delete a refund record by ID
    public function deleteRefund($id) {
        $stmt = $this->pdo->prepare("DELETE FROM `transaction_refund` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount(); // Returns the number of rows deleted
    }
}

?>
