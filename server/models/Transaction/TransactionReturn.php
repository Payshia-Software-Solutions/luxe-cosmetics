<?php

class TransactionReturn {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Retrieve all return records
    public function getAllReturns() {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_return WHERE is_active = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieve a single return record by ID
    public function getReturnById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_return WHERE id = :id AND is_active = 1");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new return record
    public function createReturn($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO transaction_return (rtn_number, customer_id, location_id, created_at, updated_by, reason, refund_id, is_active, ref_invoice, return_amount)
            VALUES (:rtn_number, :customer_id, :location_id, :created_at, :updated_by, :reason, :refund_id, :is_active, :ref_invoice, :return_amount)
        ");
        $stmt->execute([
            ':rtn_number' => $data['rtn_number'],
            ':customer_id' => $data['customer_id'],
            ':location_id' => $data['location_id'],
            ':created_at' => $data['created_at'],
            ':updated_by' => $data['updated_by'],
            ':reason' => $data['reason'],
            ':refund_id' => isset($data['refund_id']) ? $data['refund_id'] : null,
            ':is_active' => 1,
            ':ref_invoice' => $data['ref_invoice'],
            ':return_amount' => $data['return_amount']
        ]);
    }

    // Update an existing return record
    public function updateReturn($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE transaction_return
            SET rtn_number = :rtn_number, customer_id = :customer_id, location_id = :location_id, updated_by = :updated_by, reason = :reason, 
                refund_id = :refund_id, ref_invoice = :ref_invoice, return_amount = :return_amount
            WHERE id = :id
        ");
        $stmt->execute([
            ':rtn_number' => $data['rtn_number'],
            ':customer_id' => $data['customer_id'],
            ':location_id' => $data['location_id'],
            ':updated_by' => $data['updated_by'],
            ':reason' => $data['reason'],
            ':refund_id' => isset($data['refund_id']) ? $data['refund_id'] : null,
            ':ref_invoice' => $data['ref_invoice'],
            ':return_amount' => $data['return_amount'],
            ':id' => $id
        ]);
    }

    // Delete a return record by marking it inactive
    public function deleteReturn($id) {
        $stmt = $this->pdo->prepare("UPDATE transaction_return SET is_active = 0 WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}
