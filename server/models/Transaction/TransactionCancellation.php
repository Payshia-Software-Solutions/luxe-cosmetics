<?php

class TransactionCancellation {
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fetch all transaction cancellations
    public function getAllCancellations() {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_cancellation` ORDER BY `created_at` DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single transaction cancellation by ID
    public function getCancellationById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_cancellation` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new transaction cancellation
    public function createCancellation($data) {
        $stmt = $this->pdo->prepare("INSERT INTO `transaction_cancellation` (`cancellation_type`, `ref_key`, `reason`, `created_by`, `created_at`) 
                                     VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['cancellation_type'],
            $data['ref_key'],
            $data['reason'],
            $data['created_by'],
            $data['created_at']
        ]);
        return $this->pdo->lastInsertId(); // Return the ID of the newly created transaction cancellation
    }

    // Update an existing transaction cancellation
    public function updateCancellation($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE `transaction_cancellation` SET 
                                     `cancellation_type` = ?, 
                                     `ref_key` = ?, 
                                     `reason` = ?, 
                                     `created_by` = ? 
                                     WHERE `id` = ?");
        $stmt->execute([
            $data['cancellation_type'],
            $data['ref_key'],
            $data['reason'],
            $data['created_by'],
            $id
        ]);
        return $stmt->rowCount(); // Returns the number of rows affected
    }

    // Delete a transaction cancellation by ID
    public function deleteCancellation($id) {
        $stmt = $this->pdo->prepare("DELETE FROM `transaction_cancellation` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount(); // Returns the number of rows deleted
    }
}
?>
