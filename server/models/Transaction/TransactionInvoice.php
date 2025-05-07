<?php

class TransactionInvoice
{
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Generate a new invoice number
    public function generateInvoiceNumber($prefix = "TJ-WEB-INT")
    {
        // Prepare SQL query to count invoices starting with the given prefix
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS invoice_count 
                                 FROM `transaction_invoice` 
                                 WHERE `invoice_number` LIKE :prefix");

        // Execute the query with the prefix parameter
        $stmt->execute(['prefix' => $prefix . '%']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Get the count of invoices starting with the prefix
        $invoiceCount = $result['invoice_count'] ?? 0;

        // Increment the count to generate the next invoice number
        $newInvoiceNumber = $invoiceCount + 1;

        // Generate the formatted invoice number
        return $prefix . str_pad($newInvoiceNumber, 6, '0', STR_PAD_LEFT); // Example: TJ-WEB-INT000001
    }

    // Fetch all transaction invoices
    public function getAllInvoices()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_invoice` ORDER BY `id` ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single invoice by ID
    public function getInvoiceById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `transaction_invoice` WHERE `invoice_number` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new transaction invoice
    public function createInvoice($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO `transaction_invoice` (
            `invoice_number`, `invoice_date`, `inv_amount`, `grand_total`, `discount_amount`, 
            `discount_percentage`, `customer_code`, `service_charge`, `tendered_amount`, 
            `close_type`, `invoice_status`, `current_time`, `location_id`, `table_id`, 
            `order_ready_status`, `created_by`, `is_active`, `steward_id`, `cost_value`, 
            `remark`, `ref_hold`,`payment_status`, `promo_code_id`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $data['invoice_number'],
            $data['invoice_date'],
            $data['inv_amount'],
            $data['grand_total'],
            $data['discount_amount'],
            $data['discount_percentage'],
            $data['customer_code'],
            $data['service_charge'],
            $data['tendered_amount'],
            $data['close_type'],
            $data['invoice_status'],
            $data['current_time'],
            $data['location_id'],
            $data['table_id'],
            $data['order_ready_status'],
            $data['created_by'],
            $data['is_active'],
            $data['steward_id'],
            $data['cost_value'],
            $data['remark'],
            $data['ref_hold'],
            $data['payment_status'],
            $data['promo_code_id']
        ]);
        return $this->pdo->lastInsertId(); // Return the ID of the newly created invoice
    }

    // Update an existing invoice
    public function updateInvoice($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE `transaction_invoice` SET 
            `invoice_number` = ?, 
            `invoice_date` = ?, 
            `inv_amount` = ?, 
            `grand_total` = ?, 
            `discount_amount` = ?, 
            `discount_percentage` = ?, 
            `customer_code` = ?, 
            `service_charge` = ?, 
            `tendered_amount` = ?, 
            `close_type` = ?, 
            `invoice_status` = ?, 
            `current_time` = ?, 
            `location_id` = ?, 
            `table_id` = ?, 
            `order_ready_status` = ?, 
            `created_by` = ?, 
            `is_active` = ?, 
            `steward_id` = ?, 
            `cost_value` = ?, 
            `remark` = ?, 
            `ref_hold` = ?, 
            `payment_status` = ?,
            `promo_code_id` = ?
            WHERE `id` = ?");

        $stmt->execute([
            $data['invoice_number'],
            $data['invoice_date'],
            $data['inv_amount'],
            $data['grand_total'],
            $data['discount_amount'],
            $data['discount_percentage'],
            $data['customer_code'],
            $data['service_charge'],
            $data['tendered_amount'],
            $data['close_type'],
            $data['invoice_status'],
            $data['current_time'],
            $data['location_id'],
            $data['table_id'],
            $data['order_ready_status'],
            $data['created_by'],
            $data['is_active'],
            $data['steward_id'],
            $data['cost_value'],
            $data['remark'],
            $data['ref_hold'],
            $data['payment_status'],
            $data['promo_code_id'],
            $id
        ]);
        return $stmt->rowCount(); // Returns the number of rows affected
    }

    public function updateInvoiceStatus($id)
    {
        // Debug: Check if the invoice exists
        $checkStmt = $this->pdo->prepare("SELECT * FROM `transaction_invoice` WHERE `invoice_number` = ?");
        $checkStmt->execute([$id]);
        if ($checkStmt->rowCount() === 0) {
            echo "Invoice not found.";
            return 0;
        }

        // Update the status
        $stmt = $this->pdo->prepare("UPDATE `transaction_invoice` SET 
        `payment_status` = 'Paid' WHERE `invoice_number` = ?");

        $stmt->execute([$id]);
        return $stmt->rowCount(); // Returns the number of rows affected
    }



    // Delete a transaction invoice by ID
    public function deleteInvoice($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `transaction_invoice` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount(); // Returns the number of rows deleted
    }
}
