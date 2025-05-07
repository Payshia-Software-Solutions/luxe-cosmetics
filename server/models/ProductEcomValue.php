<?php
class ProductEcomValue
{
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fetch all product e-commerce values
    public function getAllProductEcomValues()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `master_products_ecom_values`");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single product e-commerce value by ID
    public function getProductEcomValueById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `master_products_ecom_values` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch a single product e-commerce value by SKU/Barcode
    public function getProductEcomValueBySkuBarcode($skuBarcode)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `master_products_ecom_values` WHERE `sku_barcode` = ?");
        $stmt->execute([$skuBarcode]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new product e-commerce value
    public function createProductEcomValue($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO `master_products_ecom_values` 
                                     (`use_for`, `sku_barcode`, `Benefits`, `How_to_do_the_Patch_test`, 
                                      `Ingredients`, `createdby`, `createdat`, `updateby`, `updateat`) 
                                     VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, NOW())");
        $stmt->execute([
            $data['use_for'],
            $data['sku_barcode'],
            $data['Benefits'],
            $data['How_to_do_the_Patch_test'],
            $data['Ingredients'],
            $data['createdby'],
            $data['updateby']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Update an existing product e-commerce value
    public function updateProductEcomValue($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE `master_products_ecom_values` SET 
                                     `use_for` = ?, 
                                     `sku_barcode` = ?, 
                                     `Benefits` = ?, 
                                     `How_to_do_the_Patch_test` = ?, 
                                     `Ingredients` = ?, 
                                     `updateby` = ?, 
                                     `updateat` = NOW()
                                     WHERE `id` = ?");
        $stmt->execute([
            $data['use_for'],
            $data['sku_barcode'],
            $data['Benefits'],
            $data['How_to_do_the_Patch_test'],
            $data['Ingredients'],
            $data['updateby'],
            $id
        ]);
        return $stmt->rowCount();
    }

    // Update a record by SKU/Barcode
    public function updateRecordBySkuBarcode($skuBarcode, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE `master_products_ecom_values` SET 
                                     `use_for` = ?, 
                                     `Benefits` = ?, 
                                     `How_to_do_the_Patch_test` = ?, 
                                     `Ingredients` = ?, 
                                     `updateby` = ?, 
                                     `updateat` = NOW()
                                     WHERE `sku_barcode` = ?");
        $stmt->execute([
            $data['use_for'],
            $data['Benefits'],
            $data['How_to_do_the_Patch_test'],
            $data['Ingredients'],
            $data['updateby'],
            $skuBarcode
        ]);
        return $stmt->rowCount();
    }

    // Delete a product e-commerce value by ID
    public function deleteProductEcomValue($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `master_products_ecom_values` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}