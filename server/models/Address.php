<?php
class Address
{
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Create a new address
    public function createAddress($data)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO `addresses` (`invoice_number`, `address_type`, `country`, `first_name`, `last_name`, `address`, `apartment`, `city`, `postal_code`, `phone`, `created_at`) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())"
        );
        $stmt->execute([
            $data['invoice_number'],
            $data['address_type'],
            $data['country'],
            $data['first_name'],
            $data['last_name'],
            $data['address'],
            $data['apartment'] ?? null,
            $data['city'],
            $data['postal_code'],
            $data['phone']
        ]);
        return $this->pdo->lastInsertId(); // Return the ID of the newly created address
    }

    // Fetch addresses by invoice number
    public function getAddressesByInvoice($invoice_number)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `addresses` WHERE `invoice_number` = ?");
        $stmt->execute([$invoice_number]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Delete an address by ID
    public function deleteAddress($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `addresses` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount(); // Returns the number of rows deleted
    }
}
