<?php

class TransactionExpensesTypes {
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fetch all expense types
    public function getAllTypes() {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_expenses_types` WHERE `is_active` = 1 ORDER BY `id` ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single expense type by ID
    public function getTypeById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_expenses_types` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new expense type
    public function createType($data) {
        $stmt = $this->pdo->prepare("INSERT INTO `transaction_expenses_types` (`type`, `is_active`) VALUES (?, ?)");
        $stmt->execute([
            $data['type'],
            $data['is_active']
        ]);
        return $this->pdo->lastInsertId(); // Return the ID of the newly created type
    }

    // Update an existing expense type
    public function updateType($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE `transaction_expenses_types` SET `type` = ?, `is_active` = ? WHERE `id` = ?");
        $stmt->execute([
            $data['type'],
            $data['is_active'],
            $id
        ]);
        return $stmt->rowCount(); // Returns the number of rows affected
    }

    // Delete an expense type by ID
    public function deleteType($id) {
        $stmt = $this->pdo->prepare("DELETE FROM `transaction_expenses_types` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount(); // Returns the number of rows deleted
    }
}
?>
