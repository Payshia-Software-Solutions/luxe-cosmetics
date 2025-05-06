<?php

class TransactionInvoiceItem
{
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fetch all transaction invoice items
    public function getAllItems()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_invoice_items` ORDER BY `id` ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single transaction invoice item by ID
    public function getItemById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_invoice_items` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRecordsByInvoice($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_invoice_items` WHERE `invoice_number` = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Create a new transaction invoice item
    public function createItem($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO `transaction_invoice_items` (
            `user_id`, `product_id`, `item_price`, `item_discount`, `quantity`, 
            `added_date`, `is_active`, `customer_id`, `hold_status`, `table_id`, 
            `invoice_number`, `cost_price`, `printed_status`, `item_remark`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $data['user_id'],
            $data['product_id'],
            $data['item_price'],
            $data['item_discount'],
            $data['quantity'],
            $data['added_date'],
            $data['is_active'],
            $data['customer_id'],
            $data['hold_status'],
            $data['table_id'],
            $data['invoice_number'],
            $data['cost_price'],
            $data['printed_status'],
            $data['item_remark']
        ]);
        return $this->pdo->lastInsertId(); // Return the ID of the newly created item
    }

    public function createItems($items)
    {
        // Prepare the SQL statement for batch insert
        $sql = "INSERT INTO `transaction_invoice_items` (
                `user_id`, `product_id`, `item_price`, `item_discount`, `quantity`, 
                `added_date`, `is_active`, `customer_id`, `hold_status`, `table_id`, 
                `invoice_number`, `cost_price`, `printed_status`, `item_remark`
            ) VALUES ";

        // Build placeholders for batch insert
        $placeholders = [];
        $values = [];
        foreach ($items as $item) {
            $placeholders[] = "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $values = array_merge($values, [
                $item['user_id'],
                $item['product_id'],
                $item['item_price'],
                $item['item_discount'],
                $item['quantity'],
                $item['added_date'],
                $item['is_active'],
                $item['customer_id'],
                $item['hold_status'],
                $item['table_id'],
                $item['invoice_number'],
                $item['cost_price'],
                $item['printed_status'],
                $item['item_remark'],
            ]);
        }

        // Combine SQL and placeholders
        $sql .= implode(", ", $placeholders);

        // Execute the batch insert
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);

        // Return the number of rows inserted
        return $stmt->rowCount();
    }


    // Update an existing transaction invoice item
    public function updateItem($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE `transaction_invoice_items` SET 
            `user_id` = ?, 
            `product_id` = ?, 
            `item_price` = ?, 
            `item_discount` = ?, 
            `quantity` = ?, 
            `added_date` = ?, 
            `is_active` = ?, 
            `customer_id` = ?, 
            `hold_status` = ?, 
            `table_id` = ?, 
            `invoice_number` = ?, 
            `cost_price` = ?, 
            `printed_status` = ?, 
            `item_remark` = ? 
            WHERE `id` = ?");

        $stmt->execute([
            $data['user_id'],
            $data['product_id'],
            $data['item_price'],
            $data['item_discount'],
            $data['quantity'],
            $data['added_date'],
            $data['is_active'],
            $data['customer_id'],
            $data['hold_status'],
            $data['table_id'],
            $data['invoice_number'],
            $data['cost_price'],
            $data['printed_status'],
            $data['item_remark'],
            $id
        ]);
        return $stmt->rowCount(); // Returns the number of rows affected
    }

    // Delete a transaction invoice item by ID
    public function deleteItem($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `transaction_invoice_items` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount(); // Returns the number of rows deleted
    }
}
