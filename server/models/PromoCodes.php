<?php

class PromoCodes
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fetch all promo codes
    public function getAllPromoCodes()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `promo_codes`");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single promo code by ID
    public function getPromoCodeById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `promo_codes` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch promo code info using promo code
    public function getPromoCodeByCode($promo_code)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `promo_codes` WHERE `code` = ?");
        $stmt->execute([$promo_code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new promo code
    public function createPromoCode($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO `promo_codes` (
            `code`, 
            `discount_type`, 
            `discount_value`, 
            `start_date`, 
            `end_date`, 
            `max_uses`, 
            `uses_count`, 
            `min_order_value`, 
            `is_active`, 
            `created_at`, 
            `updated_at`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $data['code'],
            $data['discount_type'],
            $data['discount_value'],
            $data['start_date'],
            $data['end_date'],
            $data['max_uses'],
            $data['uses_count'],
            $data['min_order_value'],
            $data['is_active'],
            $data['created_at'],
            $data['updated_at']
        ]);
    }

    // Update an existing promo code
    public function updatePromoCode($promo_code_id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE `promo_codes` SET 
            `code` = ?, 
            `discount_type` = ?, 
            `discount_value` = ?, 
            `start_date` = ?, 
            `end_date` = ?, 
            `max_uses` = ?, 
            `uses_count` = ?, 
            `min_order_value` = ?, 
            `is_active` = ?, 
            `created_at` = ?, 
            `updated_at` = ? 
            WHERE `id` = ?");

        $stmt->execute([
            $data['code'],
            $data['discount_type'],
            $data['discount_value'],
            $data['start_date'],
            $data['end_date'],
            $data['max_uses'],
            $data['uses_count'],
            $data['min_order_value'],
            $data['is_active'],
            $data['created_at'],
            $data['updated_at'],
            $promo_code_id
        ]);
    }

    // Delete a promo code by ID
    public function deletePromoCode($promo_code_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `promo_codes` WHERE `id` = ?");
        $stmt->execute([$promo_code_id]);
    }
}
