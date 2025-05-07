<?php

class TransactionInvoiceAddress
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fetch all addresses
    public function getAllAddresses()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_invoice_address`");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single address by ID
    public function getAddressById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_invoice_address` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRecordsByInvoice($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_invoice_address` WHERE `order_id` = ?");
        $stmt->execute([$id]);
        $addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Initialize arrays to store shipping and billing addresses
        $shippingAddress = null;
        $billingAddress = null;

        // Loop through the results and separate them based on address type
        foreach ($addresses as $address) {
            if ($address['address_type'] == 'shipping') {
                $shippingAddress = $address;
            } elseif ($address['address_type'] == 'billing') {
                $billingAddress = $address;
            }
        }

        // Return an array with shipping and billing addresses
        return [
            'shipping' => $shippingAddress,
            'billing' => $billingAddress
        ];
    }


    // Create a new address
    public function createAddress($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO `transaction_invoice_address` (
            `user_id`, 
            `order_id`, 
            `address_type`, 
            `first_name`, 
            `last_name`, 
            `phone`, 
            `address_line1`, 
            `address_line2`, 
            `city`, 
            `state`, 
            `postal_code`, 
            `country`, 
            `is_default`, 
            `save_info`, 
            `created_at`, 
            `updated_at`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $data['user_id'],
            $data['order_id'],
            $data['address_type'],
            $data['first_name'],
            $data['last_name'],
            $data['phone'],
            $data['address_line1'],
            $data['address_line2'],
            $data['city'],
            $data['state'],
            $data['postal_code'],
            $data['country'],
            $data['is_default'],
            $data['save_info'],
            $data['created_at'],
            $data['updated_at']
        ]);
    }

    // Update an existing address
    public function updateAddress($address_id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE `transaction_invoice_address` SET 
            `user_id` = ?, 
            `order_id` = ?, 
            `address_type` = ?, 
            `first_name` = ?, 
            `last_name` = ?, 
            `phone` = ?, 
            `address_line1` = ?, 
            `address_line2` = ?, 
            `city` = ?, 
            `state` = ?, 
            `postal_code` = ?, 
            `country` = ?, 
            `is_default` = ?, 
            `save_info` = ?, 
            `created_at` = ?, 
            `updated_at` = ? 
            WHERE `id` = ?");

        $stmt->execute([
            $data['user_id'],
            $data['order_id'],
            $data['address_type'],
            $data['first_name'],
            $data['last_name'],
            $data['phone'],
            $data['address_line1'],
            $data['address_line2'],
            $data['city'],
            $data['state'],
            $data['postal_code'],
            $data['country'],
            $data['is_default'],
            $data['save_info'],
            $data['created_at'],
            $data['updated_at'],
            $address_id
        ]);
    }

    // Delete an address by ID
    public function deleteAddress($address_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `transaction_invoice_address` WHERE `id` = ?");
        $stmt->execute([$address_id]);
    }
}
