<?php

class TransactionExpenses {
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fetch all expenses
    public function getAllExpenses() {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_expenses` WHERE `is_active` = 1 ORDER BY `updated_at` DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single expense by ID
    public function getExpenseById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_expenses` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new expense
    public function createExpense($data) {
        $stmt = $this->pdo->prepare("INSERT INTO `transaction_expenses` (`expense_id`, `ex_type`, `ex_description`, `amount`, `updated_by`, `updated_at`, `location_id`, `is_active`) 
                                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['expense_id'],
            $data['ex_type'],
            $data['ex_description'],
            $data['amount'],
            $data['updated_by'],
            $data['updated_at'],
            $data['location_id'],
            $data['is_active']
        ]);
        return $this->pdo->lastInsertId(); // Return the ID of the newly created expense
    }

    // Update an existing expense
    public function updateExpense($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE `transaction_expenses` SET 
                                     `expense_id` = ?, 
                                     `ex_type` = ?, 
                                     `ex_description` = ?, 
                                     `amount` = ?, 
                                     `updated_by` = ?, 
                                     `updated_at` = ?, 
                                     `location_id` = ?, 
                                     `is_active` = ?
                                     WHERE `id` = ?");
        $stmt->execute([
            $data['expense_id'],
            $data['ex_type'],
            $data['ex_description'],
            $data['amount'],
            $data['updated_by'],
            $data['updated_at'],
            $data['location_id'],
            $data['is_active'],
            $id
        ]);
        return $stmt->rowCount(); // Returns the number of rows affected
    }

    // Delete an expense by ID
    public function deleteExpense($id) {
        $stmt = $this->pdo->prepare("DELETE FROM `transaction_expenses` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount(); // Returns the number of rows deleted
    }
}
?>
