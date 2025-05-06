<?php

class MasterProductImages
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fetch all product images
    public function getAllImages()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `master_product_images`");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single image by ID
    public function getImageById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `master_product_images` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getImageByProductId($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `master_product_images` WHERE `product_id` = ? AND `is_active` LIKE 1");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getImageByProductIdAdmin($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `master_product_images` WHERE `product_id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Create a new product image
    public function createImage($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO `master_product_images` (
            `product_id`, `image_prefix`, `image_path`, `is_active`, `created_by`, `created_at`
        ) VALUES (?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $data['product_id'],
            $data['image_prefix'],
            $data['image_path'],
            $data['is_active'],
            $data['created_by'],
            $data['created_at']
        ]);
    }

    // Update an existing product image
    public function updateImage($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE `master_product_images` SET 
            `product_id` = ?, 
            `image_prefix` = ?, 
            `image_path` = ?, 
            `is_active` = ?, 
            `created_by` = ?, 
            `created_at` = ? 
            WHERE `id` = ?");

        $stmt->execute([
            $data['product_id'],
            $data['image_prefix'],
            $data['image_path'],
            $data['is_active'],
            $data['created_by'],
            $data['created_at'],
            $id
        ]);
    }

    public function updateImageStatus($id, $isActive)
    {
        // Update the 'is_active' field for the image with the given ID
        $sql = "UPDATE master_product_images SET is_active = :is_active WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':is_active' => $isActive,
            ':id' => $id
        ]);
    }


    // Delete a product image by ID
    public function deleteImage($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `master_product_images` WHERE `id` = ?");
        $stmt->execute([$id]);
    }
}
