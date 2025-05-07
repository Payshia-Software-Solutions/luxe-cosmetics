<?php

class TransactionQuotation {
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fetch all quotations
    public function getAllQuotations() {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_quotation` ORDER BY `id` ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single quotation by ID
    public function getQuotationById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_quotation` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new quotation
    public function createQuotation($data) {
        $stmt = $this->pdo->prepare("INSERT INTO `transaction_quotation` (
            `quote_number`, `quote_date`, `quote_amount`, `grand_total`, 
            `discount_amount`, `discount_percentage`, `customer_code`, 
            `service_charge`, `close_type`, `invoice_status`, 
            `current_time`, `location_id`, `created_by`, `is_active`, 
            `cost_value`, `remark`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $data['quote_number'],
            $data['quote_date'],
            $data['quote_amount'],
            $data['grand_total'],
            $data['discount_amount'],
            $data['discount_percentage'],
            $data['customer_code'],
            $data['service_charge'],
            $data['close_type'],
            $data['invoice_status'],
            $data['current_time'],
            $data['location_id'],
            $data['created_by'],
            $data['is_active'],
            $data['cost_value'],
            $data['remark']
        ]);
        return $this->pdo->lastInsertId(); // Return the ID of the newly created quotation
    }

    // Update an existing quotation
    public function updateQuotation($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE `transaction_quotation` SET 
            `quote_number` = ?, 
            `quote_date` = ?, 
            `quote_amount` = ?, 
            `grand_total` = ?, 
            `discount_amount` = ?, 
            `discount_percentage` = ?, 
            `customer_code` = ?, 
            `service_charge` = ?, 
            `close_type` = ?, 
            `invoice_status` = ?, 
            `current_time` = ?, 
            `location_id` = ?, 
            `created_by` = ?, 
            `is_active` = ?, 
            `cost_value` = ?, 
            `remark` = ? 
            WHERE `id` = ?");

        $stmt->execute([
            $data['quote_number'],
            $data['quote_date'],
            $data['quote_amount'],
            $data['grand_total'],
            $data['discount_amount'],
            $data['discount_percentage'],
            $data['customer_code'],
            $data['service_charge'],
            $data['close_type'],
            $data['invoice_status'],
            $data['current_time'],
            $data['location_id'],
            $data['created_by'],
            $data['is_active'],
            $data['cost_value'],
            $data['remark'],
            $id
        ]);
        return $stmt->rowCount(); // Returns the number of rows affected
    }

    // Delete a quotation by ID
    public function deleteQuotation($id) {
        $stmt = $this->pdo->prepare("DELETE FROM `transaction_quotation` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount(); // Returns the number of rows deleted
    }
}
?>
