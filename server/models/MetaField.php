<?php

class MetaField {
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fetch all active meta fields
    public function getAllMetaFields() {
        $stmt = $this->pdo->prepare("SELECT * FROM `meta_field` WHERE `status` = 1 ORDER BY `id` ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single meta field by ID
    public function getMetaFieldById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM `meta_field` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new meta field
    public function createMetaField($data) {
        $stmt = $this->pdo->prepare("INSERT INTO `meta_field` (`name`, `description`, `status`) 
                                     VALUES (?, ?, ?)");
        $stmt->execute([
            $data['name'],
            $data['description'],
            $data['status']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Update an existing meta field
    public function updateMetaField($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE `meta_field` SET 
                                     `name` = ?, 
                                     `description` = ?, 
                                     `status` = ? 
                                     WHERE `id` = ?");
        $stmt->execute([
            $data['name'],
            $data['description'],
            $data['status'],
            $id
        ]);
        return $stmt->rowCount();
    }

    // Delete a meta field by ID
    public function deleteMetaField($id) {
        $stmt = $this->pdo->prepare("DELETE FROM `meta_field` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>
