<?php

class MetaFieldProduct {
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fetch all meta field products
    public function getAllMetaFieldProducts() {
        $stmt = $this->pdo->prepare("SELECT * FROM `meta_field_product` ORDER BY `id` ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single meta field product by ID
    public function getMetaFieldProductById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM `meta_field_product` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch meta field products by meta field ID
    public function getMetaFieldProductsByMetaFieldId($metaFieldId) {
        $stmt = $this->pdo->prepare("SELECT * FROM `meta_field_product` WHERE `meta_field_id` = ? ORDER BY `id` ASC");
        $stmt->execute([$metaFieldId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch meta field product with related meta field data
    public function getMetaFieldProductWithDetails($id) {
        $stmt = $this->pdo->prepare("SELECT mfp.*, mf.name as meta_field_name, mf.description as meta_field_description 
                                     FROM `meta_field_product` mfp 
                                     JOIN `meta_field` mf ON mfp.meta_field_id = mf.id 
                                     WHERE mfp.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new meta field product
    public function createMetaFieldProduct($data) {
        $stmt = $this->pdo->prepare("INSERT INTO `meta_field_product` (`meta_field_id`, `value`) 
                                     VALUES (?, ?)");
        $stmt->execute([
            $data['meta_field_id'],
            $data['value']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Update an existing meta field product
    public function updateMetaFieldProduct($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE `meta_field_product` SET 
                                     `meta_field_id` = ?, 
                                     `value` = ? 
                                     WHERE `id` = ?");
        $stmt->execute([
            $data['meta_field_id'],
            $data['value'],
            $id
        ]);
        return $stmt->rowCount();
    }

    // Delete a meta field product by ID
    public function deleteMetaFieldProduct($id) {
        $stmt = $this->pdo->prepare("DELETE FROM `meta_field_product` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }

    // Delete all meta field products by meta field ID
    public function deleteMetaFieldProductsByMetaFieldId($metaFieldId) {
        $stmt = $this->pdo->prepare("DELETE FROM `meta_field_product` WHERE `meta_field_id` = ?");
        $stmt->execute([$metaFieldId]);
        return $stmt->rowCount();
    }

    // Check if a meta field product exists
    public function metaFieldProductExists($id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM `meta_field_product` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }

    // Get count of meta field products for a specific meta field
    public function getMetaFieldProductCount($metaFieldId) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM `meta_field_product` WHERE `meta_field_id` = ?");
        $stmt->execute([$metaFieldId]);
        return $stmt->fetchColumn();
    }
}
?>