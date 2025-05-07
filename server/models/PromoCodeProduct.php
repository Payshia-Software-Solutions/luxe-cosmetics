<?php
class PromoCodeProduct
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Create or update promo code product
    public function createPromoCodeProduct($data)
    {
        // Check if the product already exists with the same promo_code
        $stmt = $this->pdo->prepare("
        SELECT * FROM `promo_code_products`
        WHERE `promo_code` = ? AND `product_id` = ?
    ");
        $stmt->execute([$data['promo_code'], $data['product_id']]);
        $existingProduct = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingProduct) {
            // If exists, update the status (unchecked status will set to 0 or inactive)
            $stmt = $this->pdo->prepare("
            UPDATE `promo_code_products`
            SET `status` = ?, `updated_at` = ?
            WHERE `promo_code` = ? AND `product_id` = ?
        ");
            $stmt->execute([
                $data['status'], // assuming 1 for checked and 0 for unchecked
                $data['updated_at'],
                $data['promo_code'],
                $data['product_id']
            ]);
            return $existingProduct['id'];  // Return the existing ID of the updated record
        } else {
            // If not exists, insert a new record
            $stmt = $this->pdo->prepare("
            INSERT INTO `promo_code_products` (`promo_code`, `product_id`, `status`, `created_at`)
            VALUES (?, ?, ?, ?)
        ");
            $stmt->execute([
                $data['promo_code'],
                $data['product_id'],
                $data['status'],  // 1 for checked, 0 for unchecked
                $data['created_at']
            ]);
            return $this->pdo->lastInsertId();  // Return the ID of the new inserted record
        }
    }


    // Fetch all promo code products
    public function getAllPromoCodeProducts()
    {
        $stmt = $this->pdo->query("SELECT * FROM `promo_code_products` ORDER BY `created_at` DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a promo code product by ID
    public function getPromoCodeProductById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `promo_code_products` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch a promo code product by ID
    public function getPromoCodeProductByPromoCode($promoCode)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `promo_code_products` WHERE `promo_code` = ?");
        $stmt->execute([$promoCode]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a promo code product by ID
    public function getPromoCodeProductByPromoCodeActive($promoCode)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `promo_code_products` WHERE `promo_code` = ? AND `status` = 1");
        $stmt->execute([$promoCode]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Delete a promo code product by ID
    public function deletePromoCodeProduct($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `promo_code_products` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
