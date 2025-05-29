<?php

date_default_timezone_set('Asia/Colombo');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require './vendor/autoload.php'; // Ensure PHPMailer is loaded via Composer
require_once './models/Payment.php';
require_once './models/MasterProduct.php';
require_once './models/Transaction/TransactionInvoice.php';
require_once './models/Transaction/TransactionReceipt.php';
require_once './models/Transaction/TransactionInvoiceItem.php';
require_once './models/TransactionInvoiceAddress.php';


class PaymentController
{
    private $pdo;
    private $model;
    private $model2;
    private $productModel;
    private $AddressModel;
    private $receiptModel;

    // @Local Keys
    // private $merchant_id = '1227940'; // Your merchant ID
    // private $merchant_secret = 'Mzc2NTYyMjM3MzQwNjY0NDAxNDcyNDU4Nzc5NjE1MzAwNTczNjA4Nw=='; // Your merchant secret
    // private $domainName = 'http://localhost:3000';
    // private $serverUrl = 'http://localhost/TeaJarWebsite/server';
    // private $modePrefix = "sandbox";


    // @Live Keys
    private $merchant_id = '239701'; // Your merchant ID
    private $merchant_secret = 'MjM0ODE1NTI3MTEyNjkwNDkxMDAzMjMyNzExMjcxMzE2MjIzODgwNg=='; // Your merchant secret
    private $domainName = 'https://teajarceylon.com';
    private $serverUrl = 'https://kduserver.payshia.com';
    private $modePrefix = "www";


    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->model = new TransactionInvoice($pdo);
        $this->model2 = new TransactionInvoiceItem($pdo);
        $this->productModel = new Product($pdo);
        $this->AddressModel = new TransactionInvoiceAddress($pdo);
        $this->receiptModel = new TransactionReceipt($pdo);
    }

    public function initiatePayment()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        // Check if all necessary POST parameters are set
        if (
            !isset(
            $data['totalAmount'],
            $data['paymentMethod'],
            $data['contactDetails'],
            $data['shippingAddress'],
            $data['sameAddressStatus'],
            $data['items'],
        )
        ) {
            // If any required field is missing, return an error
            echo json_encode(['error' => 'Missing required parameters']);
            exit;
        }

        $totalAmount = number_format($data['totalAmount'], 2, '.', ''); // Ensure amount is formatted
        $currency = "LKR";
        $return_url = $this->domainName . "/order-confirmation";
        $cancel_url = $this->domainName . "/checkout";
        $notify_url = $this->serverUrl . "/payment/notify";
        $promoCode = $data['promoCode'];  // The promo code applied to the order
        $paymentMethod = $data['paymentMethod']; // Payment method (e.g., "card")

        // Get the contact details
        $contactDetails = $data['contactDetails'];
        $email = $contactDetails['email'];
        $subscribe = $contactDetails['subscribe'];

        // Get shipping address
        $shippingAddress = $data['shippingAddress'];

        // Get billing address (will be used only if sameAddressStatus is 0)
        $billingAddress = $data['billingAddress'];

        // Check if the shipping address and billing address are the same
        $sameAddressStatus = $data['sameAddressStatus'];

        if ($sameAddressStatus == 1) {
            // If the addresses are the same, use the shipping address as the billing address
            $billingAddress = $shippingAddress;
        }

        // Customer details (shipping details)
        $customer_details = [
            'first_name' => $shippingAddress['firstName'],
            'last_name' => $shippingAddress['lastName'],
            'email' => $email,
            'phone' => $shippingAddress['phone'],
            'address' => $shippingAddress['address'],
            'city' => $shippingAddress['city'],
            'country' => $shippingAddress['country'],
            'postal_code' => $shippingAddress['postalCode']
        ];
        // var_dump($totalAmount);

        // Validate totalAmount
        if (!is_numeric($totalAmount) || $totalAmount <= 0) {
            echo json_encode(['error' => 'Invalid amount']);
            exit;
        }

        // Prepare the order items as a string
        $items = "";
        foreach ($data['items'] as $item) {
            $items .= $item['productName'] . " (ID: " . $item['id'] . ") x " . $item['quantity'] . ", ";
        }
        $items = rtrim($items, ", "); // Remove trailing comma

        // Extract order items and calculate total
        $itemsList = isset($data['items']) ? $data['items'] : [];
        $total_amount = 0;
        foreach ($itemsList as $item) {
            $total_amount += $item['price'] * $item['quantity'];  // Calculate total based on price and quantity
        }

        $invoiceNumber = uniqid();
        // Prepare the invoice data
        $invoice_data = [
            'invoice_number' => $invoiceNumber,
            'invoice_date' => date('Y-m-d'), // Current date
            'inv_amount' => $total_amount, // Total amount before discount
            'grand_total' => $data['totalAmount'], // Final amount after discount, shipping, etc.
            'discount_amount' => isset($data['discountAmount']) ? $data['discountAmount'] : 0,
            'discount_percentage' => isset($data['discountPercentage']) ? $data['discountPercentage'] : 0,
            'customer_code' => $customer_details['email'], // Assuming customer_code can be the email
            'service_charge' => 0, // If applicable
            'tendered_amount' => $data['totalAmount'], // Amount paid
            'close_type' => 'Pending', // Assuming paid status
            'invoice_status' => 1, // Initial status
            'current_time' => date('Y-m-d H:i:s'),
            'location_id' => 1, // Adjust as needed
            'table_id' => 1, // Adjust as needed
            'order_ready_status' => 0, // Order is not ready initially
            'created_by' => 'Online', // Change to the actual user or system responsible
            'is_active' => 1,
            'steward_id' => 1, // Adjust as needed
            'cost_value' => $total_amount, // Assuming cost value is the same as inv_amount
            'remark' => 'Payment initiated', // Optional remark
            'ref_hold' => null, // Optional reference hold, if needed
            'promo_code_id' => $promoCode, // Optional reference hold, if needed,
            'payment_status' => 'Not Paid'
        ];

        $AddressData = [
            'shipping' => [
                'user_id' => $customer_details['email'] ?? null,
                'order_id' => $invoiceNumber ?? null,
                'address_type' => 'shipping',
                'first_name' => $shippingAddress['firstName'],
                'last_name' => $shippingAddress['lastName'],
                'phone' => $shippingAddress['phone'],
                'address_line1' => $shippingAddress['address'],
                'address_line2' => $shippingAddress['address_line2'] ?? null,
                'city' => $shippingAddress['city'],
                'state' => $shippingAddress['state'] ?? null,
                'postal_code' => $shippingAddress['postalCode'],
                'country' => $shippingAddress['country'],
                'is_default' => $shippingAddress['is_default'] ?? 0,
                'save_info' => $shippingAddress['save_info'] ?? 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            'billing' => [
                'user_id' => $customer_details['email'] ?? null,
                'order_id' => $invoiceNumber ?? null,
                'address_type' => 'billing',
                'first_name' => $billingAddress['firstName'],
                'last_name' => $billingAddress['lastName'],
                'phone' => $billingAddress['phone'],
                'address_line1' => $billingAddress['address'],
                'address_line2' => $billingAddress['address_line2'] ?? null,
                'city' => $billingAddress['city'],
                'state' => $billingAddress['state'] ?? null,
                'postal_code' => $billingAddress['postalCode'],
                'country' => $billingAddress['country'],
                'is_default' => $billingAddress['is_default'] ?? 0,
                'save_info' => $billingAddress['save_info'] ?? 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $emailItems = [];
        foreach ($itemsList as $item) {
            $emailItems[] = [
                "image_url" => "https://kdu-admin.payshia.com/pos-system/assets/images/products/" . $item['id'] . "/" . $item['imgUrl'],
                "name" => $item['productName'],
                "quantity" => $item['quantity'],
                "price" => $item['price']
            ];
        }

        // Call the createInvoice method to insert the data
        $invoiceId = $this->model->createInvoice($invoice_data);
        foreach ($AddressData as $address) {
            $addressSaveStatus = $this->AddressModel->createAddress($address);
        }

        // If invoice was created successfully, proceed with payment gateway
        if ($invoiceId && $paymentMethod == 'card') {
            // Prepare items for saving
            $invoiceItems = [];
            foreach ($itemsList as $item) {
                $invoiceItems[] = [
                    'user_id' => $customer_details['email'] ?? 1, // Replace with actual user logic
                    'product_id' => $item['id'],
                    'item_price' => $item['price'],
                    'item_discount' => $item['discount'] ?? 0,
                    'quantity' => $item['quantity'],
                    'added_date' => date('Y-m-d H:i:s'),
                    'is_active' => 1,
                    'customer_id' => $customer_details['email'] ?? null,
                    'hold_status' => $data['hold_status'] ?? 0,
                    'table_id' => 0,
                    'invoice_number' => $invoiceNumber,
                    'cost_price' => $item['cost_price'] ?? $item['price'], // Adjust if cost differs
                    'printed_status' => 0,
                    'item_remark' => $item['remark'] ?? null,
                ];
            }

            // Save items using batch insert
            $this->model2->createItems($invoiceItems);


            // Respond with success
            http_response_code(201);
            echo json_encode([
                'message' => 'Invoice and items created successfully',
                'invoice_id' => $invoiceId,
                'total_amount' => $total_amount,
            ]);

            // Generate the hash for security
            $hash = $this->generateHash($invoiceNumber, $totalAmount, $currency);

            // Prepare the form data for submission
            $form_data = array_merge([
                'merchant_id' => $this->merchant_id,
                'return_url' => $return_url,
                'cancel_url' => $cancel_url,
                'notify_url' => $notify_url,
                'order_id' => $invoiceNumber,
                'items' => $items, // Order items in a readable string format
                'currency' => $currency,
                'amount' => $totalAmount,
                'paymentMethod' => $paymentMethod, // Selected payment method
                'hash' => $hash
            ], $customer_details);

            // Generate the HTML form that will auto-submit to PayHere checkout
            echo '<html><body onload="document.forms[0].submit();">';
            echo '<form method="post" action="https://' . $this->modePrefix . '.payhere.lk/pay/checkout">';

            foreach ($form_data as $key => $value) {
                echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
            }
            echo "Redirecting...";
            echo '</form></body></html>';
        } else {
            echo json_encode(['error' => 'Failed to create invoice']);
        }
    }

    private function generateHash($invoiceNumber, $totalAmount, $currency)
    {
        return strtoupper(
            md5(
                $this->merchant_id .
                $invoiceNumber .
                $totalAmount .
                $currency .
                strtoupper(md5($this->merchant_secret))
            )
        );
    }

    public function initiateCodInvoice()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        // Check if all necessary POST parameters are set
        if (
            !isset(
            $data['totalAmount'],
            $data['paymentMethod'],
            $data['contactDetails'],
            $data['shippingAddress'],
            $data['sameAddressStatus'],
            $data['items'],
        )
        ) {
            // If any required field is missing, return an error
            echo json_encode(['error' => 'Missing required parameters']);
            exit;
        }

        // Get the payment details from the POST request
        $totalAmount = number_format($data['totalAmount'], 2, '.', ''); // Ensure amount is formatted
        $promoCode = $data['promoCode'];  // The promo code applied to the order
        $paymentMethod = $data['paymentMethod']; // Payment method (e.g., "card")

        // Get the contact details
        $contactDetails = $data['contactDetails'];
        $email = $contactDetails['email'];
        $subscribe = $contactDetails['subscribe'];

        // Get shipping address
        $shippingAddress = $data['shippingAddress'];

        // Get billing address (will be used only if sameAddressStatus is 0)
        $billingAddress = $data['billingAddress'];

        // Check if the shipping address and billing address are the same
        $sameAddressStatus = $data['sameAddressStatus'];

        if ($sameAddressStatus == 1) {
            // If the addresses are the same, use the shipping address as the billing address
            $billingAddress = $shippingAddress;
        }

        // Customer details (shipping details)
        $customer_details = [
            'first_name' => $shippingAddress['firstName'],
            'last_name' => $shippingAddress['lastName'],
            'email' => $email,
            'phone' => $shippingAddress['phone'],
            'address' => $shippingAddress['address'],
            'city' => $shippingAddress['city'],
            'country' => $shippingAddress['country'],
            'postal_code' => $shippingAddress['postalCode']
        ];
        // var_dump($totalAmount);

        // Validate totalAmount
        if (!is_numeric($totalAmount) || $totalAmount <= 0) {
            echo json_encode(['error' => 'Invalid amount']);
            exit;
        }

        // Prepare the order items as a string
        $items = "";
        foreach ($data['items'] as $item) {
            $items .= $item['productName'] . " (ID: " . $item['id'] . ") x " . $item['quantity'] . ", ";
        }
        $items = rtrim($items, ", "); // Remove trailing comma

        // Extract order items and calculate total
        $itemsList = isset($data['items']) ? $data['items'] : [];
        $total_amount = 0;
        foreach ($itemsList as $item) {
            $total_amount += $item['price'] * $item['quantity'];  // Calculate total based on price and quantity
        }

        $invoiceNumber = $this->model->generateInvoiceNumber("TJ-WEB-INV");
        // Prepare the invoice data
        $invoice_data = [
            'invoice_number' => $invoiceNumber,
            'invoice_date' => date('Y-m-d'), // Current date
            'inv_amount' => $total_amount, // Total amount before discount
            'grand_total' => $data['totalAmount'], // Final amount after discount, shipping, etc.
            'discount_amount' => isset($data['discountAmount']) ? $data['discountAmount'] : 0,
            'discount_percentage' => isset($data['discountPercentage']) ? $data['discountPercentage'] : 0,
            'customer_code' => $customer_details['email'], // Assuming customer_code can be the email
            'service_charge' => 0, // If applicable
            'tendered_amount' => $data['totalAmount'], // Amount paid
            'close_type' => 'Pending', // Assuming paid status
            'invoice_status' => 1, // Initial status
            'current_time' => date('Y-m-d H:i:s'),
            'location_id' => 1, // Adjust as needed
            'table_id' => 1, // Adjust as needed
            'order_ready_status' => 0, // Order is not ready initially
            'created_by' => 'Online', // Change to the actual user or system responsible
            'is_active' => 1,
            'steward_id' => 1, // Adjust as needed
            'cost_value' => $total_amount, // Assuming cost value is the same as inv_amount
            'remark' => 'Payment initiated', // Optional remark
            'ref_hold' => null, // Optional reference hold, if needed
            'promo_code_id' => $promoCode, // Optional reference hold, if needed,
            'payment_status' => 'COD'
        ];

        $AddressData = [
            'shipping' => [
                'user_id' => $customer_details['email'] ?? null,
                'order_id' => $invoiceNumber ?? null,
                'address_type' => 'shipping',
                'first_name' => $shippingAddress['firstName'],
                'last_name' => $shippingAddress['lastName'],
                'phone' => $shippingAddress['phone'],
                'address_line1' => $shippingAddress['address'],
                'address_line2' => $shippingAddress['address_line2'] ?? null,
                'city' => $shippingAddress['city'],
                'state' => $shippingAddress['state'] ?? null,
                'postal_code' => $shippingAddress['postalCode'],
                'country' => $shippingAddress['country'],
                'is_default' => $shippingAddress['is_default'] ?? 0,
                'save_info' => $shippingAddress['save_info'] ?? 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            'billing' => [
                'user_id' => $customer_details['email'] ?? null,
                'order_id' => $invoiceNumber ?? null,
                'address_type' => 'billing',
                'first_name' => $billingAddress['firstName'],
                'last_name' => $billingAddress['lastName'],
                'phone' => $billingAddress['phone'],
                'address_line1' => $billingAddress['address'],
                'address_line2' => $billingAddress['address_line2'] ?? null,
                'city' => $billingAddress['city'],
                'state' => $billingAddress['state'] ?? null,
                'postal_code' => $billingAddress['postalCode'],
                'country' => $billingAddress['country'],
                'is_default' => $billingAddress['is_default'] ?? 0,
                'save_info' => $billingAddress['save_info'] ?? 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $emailItems = [];
        foreach ($itemsList as $item) {
            $emailItems[] = [
                "image_url" => "https://kdu-admin.payshia.com/pos-system/assets/images/products/" . $item['id'] . "/" . $item['imgUrl'],
                "name" => $item['productName'],
                "quantity" => $item['quantity'],
                "price" => $item['price']
            ];
        }

        // Call the createInvoice method to insert the data
        $invoiceId = $this->model->createInvoice($invoice_data);
        foreach ($AddressData as $address) {
            $addressSaveStatus = $this->AddressModel->createAddress($address);
        }

        // If invoice was created successfully, proceed with payment gateway
        if ($invoiceId && $paymentMethod == 'cod') {
            // Prepare items for saving
            $invoiceItems = [];
            foreach ($itemsList as $item) {
                $invoiceItems[] = [
                    'user_id' => $customer_details['email'] ?? 1, // Replace with actual user logic
                    'product_id' => $item['id'],
                    'item_price' => $item['price'],
                    'item_discount' => $item['discount'] ?? 0,
                    'quantity' => $item['quantity'],
                    'added_date' => date('Y-m-d H:i:s'),
                    'is_active' => 1,
                    'customer_id' => $customer_details['email'] ?? null,
                    'hold_status' => $data['hold_status'] ?? 0,
                    'table_id' => 0,
                    'invoice_number' => $invoiceNumber,
                    'cost_price' => $item['cost_price'] ?? $item['price'], // Adjust if cost differs
                    'printed_status' => 0,
                    'item_remark' => $item['remark'] ?? null,
                ];
            }

            // Save items using batch insert
            $this->model2->createItems($invoiceItems);

            // Respond with success
            http_response_code(201);
            // Send Invoice
            $invoiceSendMailStatus = $this->SendInvoiceEmail($invoiceNumber);
            echo json_encode([
                'message' => 'Invoice and items created successfully',
                'invoice_id' => $invoiceNumber,
                'total_amount' => $total_amount,
            ]);
        } else {
            echo json_encode(['error' => 'Failed to create invoice']);
        }
    }
    // Create a new transaction invoice
    public function createInvoice($data)
    {

        // Validate required fields
        if (
            $data && isset($data['invoice_number']) && isset($data['invoice_date']) &&
            isset($data['inv_amount']) && isset($data['grand_total']) &&
            isset($data['discount_amount']) && isset($data['discount_percentage']) &&
            isset($data['customer_code']) && isset($data['service_charge']) &&
            isset($data['tendered_amount']) && isset($data['close_type']) &&
            isset($data['invoice_status']) && isset($data['current_time']) &&
            isset($data['location_id']) && isset($data['table_id']) &&
            isset($data['created_by']) && isset($data['is_active']) &&
            isset($data['steward_id']) && isset($data['cost_value']) && isset($data['payment_status'])
        ) {

            $data['current_time'] = date('Y-m-d H:i:s'); // Set current timestamp
            $this->model->createInvoice($data);
            http_response_code(201);
            echo json_encode(['message' => 'Transaction invoice created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Create a new transaction receipt
    public function createReceipt($data)
    {
        // Validate required fields
        if (
            $data && isset($data['rec_number']) && isset($data['type']) &&
            isset($data['is_active']) && isset($data['date']) &&
            isset($data['current_time']) && isset($data['amount']) &&
            isset($data['created_by']) && isset($data['ref_id']) &&
            isset($data['location_id']) && isset($data['customer_id']) &&
            isset($data['today_invoice'])
        ) {

            $data['current_time'] = date('Y-m-d H:i:s'); // Set current timestamp
            $receiptId = $this->receiptModel->createReceipt($data);

            if ($receiptId) {
                http_response_code(201);
                echo json_encode(['message' => 'Transaction receipt created successfully', 'receipt_id' => $receiptId]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to create transaction receipt']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Method to handle the return URL (after user is redirected from PayHere)
    public function paymentReturn()
    {
        // The return URL is handled after the user is redirected back to your website
        // You can query your database to check the status of the payment and display the result to the user

        $order_id = $_GET['order_id'];
        // $payment_status = $this->getOrderStatus($order_id);

        if ($order_id) {
            echo $order_id . ' Payment was successful!';
        } else {
            echo 'Payment failed or was canceled!';
        }
    }

    public function paymentNotify()
    {
        // Step 1: Retrieve the incoming payment notification data (x-www-form-urlencoded)
        $data = $_POST;

        // Debug: Log raw incoming data
        file_put_contents('logs/payment_notify_log.txt', json_encode($data) . PHP_EOL, FILE_APPEND);

        // Step 2: Validate required fields
        if (!isset($data['merchant_id'], $data['order_id'], $data['payhere_amount'], $data['payhere_currency'], $data['status_code'], $data['md5sig'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        // Step 3: Retrieve and assign fields
        $merchant_id = $data['merchant_id'];
        $order_id = $data['order_id'];
        $payhere_amount = $data['payhere_amount'];
        $payhere_currency = $data['payhere_currency'];
        $status_code = $data['status_code'];
        $md5sig = $data['md5sig'];

        // Step 5: Recreate the MD5 signature using received data and your secret key
        $local_md5sig = strtoupper(
            md5(
                $merchant_id .
                $order_id .
                $payhere_amount .
                $payhere_currency .
                $status_code .
                strtoupper(md5($this->merchant_secret))
            )
        );


        // Step 6: Verify the MD5 signature and payment status
        if ($local_md5sig === $md5sig && $status_code == 2) {
            // Step 7: Update the invoice status

            // Call the model to update the invoice status
            try {
                $rowCount = $this->model->updateInvoiceStatus($order_id);
                $InvoiceInfo = $this->model->getInvoiceById($order_id);
                $addressInfo = $this->AddressModel->getRecordsByInvoice($order_id);
                $invoiceItems = $this->model2->getRecordsByInvoice($order_id);

                $shippingAddress = $addressInfo['shipping'];
                $billingAddress = $addressInfo['billing'];


                // New Invoice
                $invoiceNumber = $this->model->generateInvoiceNumber("TJ-WEB-INV");
                $receiptNumber = $this->receiptModel->generateReceiptNumber("TJ-WEB-REC");

                // Assuming $invoice_info contains the invoice data from your array
                $invoice_data = [
                    'invoice_number' => $invoiceNumber,
                    'invoice_date' => $InvoiceInfo['invoice_date'],
                    'inv_amount' => $InvoiceInfo['inv_amount'], // Total amount before discount
                    'grand_total' => $InvoiceInfo['grand_total'], // Final amount after discount, shipping, etc.
                    'discount_amount' => $InvoiceInfo['discount_amount'],
                    'discount_percentage' => $InvoiceInfo['discount_percentage'],
                    'customer_code' => $InvoiceInfo['customer_code'], // Assuming customer_code can be the email
                    'service_charge' => $InvoiceInfo['service_charge'],
                    'tendered_amount' => $InvoiceInfo['tendered_amount'], // Amount paid
                    'close_type' => $InvoiceInfo['close_type'],
                    'invoice_status' => $InvoiceInfo['invoice_status'],
                    'current_time' => $InvoiceInfo['current_time'],
                    'location_id' => $InvoiceInfo['location_id'],
                    'table_id' => $InvoiceInfo['table_id'],
                    'order_ready_status' => $InvoiceInfo['order_ready_status'],
                    'created_by' => $InvoiceInfo['created_by'],
                    'is_active' => $InvoiceInfo['is_active'],
                    'steward_id' => $InvoiceInfo['steward_id'],
                    'cost_value' => $InvoiceInfo['cost_value'],
                    'remark' => $InvoiceInfo['remark'],
                    'ref_hold' => $order_id,
                    'promo_code_id' => $InvoiceInfo['promo_code_id'],
                    'payment_status' => isset($InvoiceInfo['payment_status']) ? $InvoiceInfo['payment_status'] : 'Not Paid'
                ];

                $AddressData = [
                    'shipping' => [
                        'user_id' => $shippingAddress['user_id'] ?? null,
                        'order_id' => $invoiceNumber ?? null,
                        'address_type' => 'shipping',
                        'first_name' => $shippingAddress['first_name'],
                        'last_name' => $shippingAddress['last_name'],
                        'phone' => $shippingAddress['phone'],
                        'address_line1' => $shippingAddress['address_line1'],
                        'address_line2' => $shippingAddress['address_line2'] ?? null,
                        'city' => $shippingAddress['city'],
                        'state' => $shippingAddress['state'] ?? null,
                        'postal_code' => $shippingAddress['postal_code'],
                        'country' => $shippingAddress['country'],
                        'is_default' => $shippingAddress['is_default'] ?? 0,
                        'save_info' => $shippingAddress['save_info'] ?? 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ],
                    'billing' => [
                        'user_id' => $billingAddress['user_id'] ?? null,
                        'order_id' => $invoiceNumber ?? null,
                        'address_type' => 'billing',
                        'first_name' => $billingAddress['first_name'],
                        'last_name' => $billingAddress['last_name'],
                        'phone' => $billingAddress['phone'],
                        'address_line1' => $billingAddress['address_line1'],
                        'address_line2' => $billingAddress['address_line2'] ?? null,
                        'city' => $billingAddress['city'],
                        'state' => $billingAddress['state'] ?? null,
                        'postal_code' => $billingAddress['postal_code'],
                        'country' => $billingAddress['country'],
                        'is_default' => $billingAddress['is_default'] ?? 0,
                        'save_info' => $billingAddress['save_info'] ?? 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]
                ];

                // Call the createInvoice method to insert the data
                $invoiceId = $this->model->createInvoice($invoice_data);
                // var_dump($addressInfo);
                foreach ($AddressData as $address) {
                    var_dump($address);
                    $addressSaveStatus = $this->AddressModel->createAddress($address);
                }
                $newInvoiceItems = [];
                foreach ($invoiceItems as $item) {
                    $newInvoiceItems[] = [
                        'user_id' => $InvoiceInfo['customer_code'] ?? 1, // Replace with actual user logic
                        'product_id' => $item['product_id'],
                        'item_price' => $item['item_price'],
                        'item_discount' => $item['item_discount'] ?? 0,
                        'quantity' => $item['quantity'],
                        'added_date' => date('Y-m-d H:i:s'),
                        'is_active' => 1,
                        'customer_id' => $InvoiceInfo['customer_code'],
                        'hold_status' => 0,
                        'table_id' => 0,
                        'invoice_number' => $invoiceNumber,
                        'cost_price' => $item['cost_price'], // Adjust if cost differs
                        'printed_status' => 0,
                        'item_remark' => null,
                    ];
                }

                // Save items using batch insert
                $this->model2->createItems($newInvoiceItems);

                // Data for creating receipt
                $receiptData = [
                    'rec_number' => $receiptNumber, // Unique receipt number
                    'type' => 1,
                    'is_active' => 1,
                    'date' => date('Y-m-d'), // Format: YYYY-MM-DD
                    'current_time' => date('Y-m-d H:i:s'),
                    'amount' => $payhere_amount,
                    'created_by' => "Payhere", // Example data
                    'ref_id' => $invoiceNumber,
                    'location_id' => 1, // Assuming location_id exists
                    'customer_id' => $InvoiceInfo['customer_code'],
                    'today_invoice' => 1,
                ];

                // Create the transaction receipt
                $receiptId = $this->receiptModel->createReceipt($receiptData);

                if (!$receiptId) {
                    echo json_encode(['error' => 'Failed to create receipt']);
                    exit;
                }

                // Check if the update was successful
                if ($rowCount > 0) {
                    // Respond with a success message
                    http_response_code(201);
                    echo json_encode(['message' => 'Transaction invoice updated successfully']);
                    // Send Invoice
                    $invoiceSendMailStatus = $this->SendInvoiceEmail($order_id);
                } else {
                    // If no rows were affected, it means the invoice was not found or updated
                    http_response_code(500);
                    echo json_encode(['error' => 'Invoice update failed']);
                }
            } catch (Exception $e) {
                // Respond with an internal server error
                http_response_code(500);
                echo json_encode(['error' => 'Internal server error during database operation']);
            }
        } else {
            // Step 8: Handle invalid checksum or payment failure
            http_response_code(400);
            echo json_encode(['error' => 'Invalid payment notification or payment failed']);
        }
    }

    public function SendInvoiceEmail($invoiceNumber)
    {
        try {
            $InvoiceInfo = $this->model->getInvoiceById($invoiceNumber);
            $invoiceItems = $this->model2->getRecordsByInvoice($invoiceNumber);
            $addressInfo = $this->AddressModel->getRecordsByInvoice($invoiceNumber);
            $shippingAddress = $addressInfo['shipping'];
            $billingAddress = $addressInfo['billing'];

            if (!$InvoiceInfo || !$invoiceItems) {
                echo "Invoice or items not found";
            }

            $itemsArray = [];
            foreach ($invoiceItems as $item) {
                $productInfo = $this->productModel->getProductById($item['product_id']);
                $productUrl = "https://kdu-admin.payshia.com/pos-system/assets/images/products/" . $item['product_id'] . "/" . $productInfo['image_path'];
                $encodedUrl = urlencode($productUrl);
                $width = 384;
                $quality = 75;

                $nextJsImageUrl = "https://teajarceylon.com/_next/image?url={$encodedUrl}&w={$width}&q={$quality}";

                $itemsArray[] = [
                    "image_url" => $nextJsImageUrl,  // Fallback if image URL is missing
                    "name" => $productInfo['product_name'],                 // Fallback if name is missing
                    "quantity" => $item['quantity'],                      // Fallback to 1 if quantity is missing
                    "price" => isset($item['item_price']) ? $item['item_price'] : '0.00'  // Handle missing or null prices safely
                ];
            }

            $orderData = [
                "order_id" => $invoiceNumber,
                "order_date" => date('Y-m-d H:i:s'),
                "customer_name" => $InvoiceInfo['customer_code'],
                "address" => $shippingAddress['address_line1'],
                "city" => $shippingAddress['city'],
                "state" => $shippingAddress['state'],
                "zip" => $shippingAddress['postal_code'],
                "country" => $shippingAddress['country'],
                "total" => $InvoiceInfo['grand_total'],
                "subtotal" => $InvoiceInfo['inv_amount'],
                "discount" => $InvoiceInfo['discount_amount'],
                "shipping" => isset($InvoiceInfo['shipping_fee']) ? $InvoiceInfo['shipping_fee'] : 0,
                "tax" => $InvoiceInfo['service_charge'],
                "tracking_url" => "https://example.com/track/12345",
                "delivery_date" => date('Y-m-d H:i:s', strtotime('+5 days', strtotime(date('Y-m-d H:i:s')))),
                "customer_service_email" => "marketing@teajarceylon.com",
                "instagram_url" => "https://www.instagram.com/teajar",
                "facebook_url" => "https://www.facebook.com/teajar",
                "pinterest_url" => "https://www.pinterest.com/teajar",
                "company_address" => "KDU Exports PVT LTD, 427 A, Galle Road, Colombo 03, Sri Lanka",
                "company_contact" => "(+94) 70 55 08 800",
                "unsubscribe_url" => "https://teajarceylon.com/unsubscribe",
                "customer_email" => $InvoiceInfo['customer_code'],
                "items" => $itemsArray,
                "payment_status" => $InvoiceInfo['payment_status'],
            ];

            $emailStatus = $this->sendOrderConfirmationEmail($orderData, $InvoiceInfo['customer_code']);
            // return json_encode($emailStatus);
            // echo json_encode($emailStatus);
        } catch (Exception $e) {
            echo "Exception: " . $e->getMessage();
        }
    }

    // Helper method to update the order status in your database
    private function updateOrderStatus($order_id, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE orders SET status = :status WHERE order_id = :order_id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
    }

    // Helper method to get the order status
    private function getOrderStatus($order_id)
    {
        $stmt = $this->pdo->prepare("SELECT status FROM orders WHERE order_id = :order_id");
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['status'] : 'not found';
    }

    public function sendOrderConfirmationEmail($orderData, $customerEmail)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'mail.teajarceylon.com';  // SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'no-reply@teajarceylon.com';  // SMTP username
            $mail->Password = 'g85zvB]2;Hnf';  // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  // Use implicit TLS encryption
            $mail->Port = 465;  // TCP port for SMTP

            // Recipients
            $mail->setFrom('no-reply@teajarceylon.com', 'Tea Jar | Finest Ceylon Tea');
            $mail->addAddress($customerEmail); // Add the customer's email

            // $mail->addCC('dupasena@kdugroup.com');
            // $mail->addCC('marketing@teajarceylon.com');
            // $mail->addCC('international@kduexports.com');

            // Generate email content
            $emailContent = $this->generateEmailHTML($orderData);

            // Content
            $mail->isHTML(true); // Email format is HTML
            $mail->Subject = 'Order Confirmation - Tea Jar'; // Email subject
            $mail->Body = $emailContent; // Email body content

            // Send the email
            $mail->send();
            return ['status' => 'success', 'message' => 'Email Sent Successfully'];
        } catch (Exception $e) {
            // Log the error
            error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
            $mailError = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return ['status' => 'error', 'message' => $mailError];
        }
    }

    /**
     * Generate the HTML for an order confirmation email.
     *
     * @param array $orderData An associative array containing order details.
     * @return string The generated email HTML.
     * @throws Exception If the email template file is not found.
     */
    public function generateEmailHTML($orderData)
    {

        // var_dump($orderData);
        // Load the email template
        $templateFile = './templates/order_template.html';
        if (!file_exists($templateFile)) {
            throw new Exception("Email template file not found: {$templateFile}");
        }

        $emailTemplate = file_get_contents($templateFile);

        // Replace placeholders with order data
        $placeholders = [
            '[ORDER_NUMBER]' => $orderData['order_id'],
            '[ORDER_DATE]' => $orderData['order_date'],
            '[CUSTOMER_NAME]' => $orderData['customer_name'],
            '[STREET_ADDRESS]' => $orderData['address'],
            '[CITY]' => $orderData['city'],
            '[STATE]' => isset($orderData['state']) ? $orderData['state'] : "",
            '[ZIP]' => $orderData['zip'],
            '[COUNTRY]' => $orderData['country'],
            '[TOTAL]' => number_format($orderData['total'], 2),
            '[SUBTOTAL]' => number_format($orderData['subtotal'], 2),
            '[DISCOUNT]' => number_format($orderData['discount'], 2),
            '[SHIPPING]' => number_format($orderData['shipping'], 2),
            '[TAX]' => number_format($orderData['tax'], 2),
            '[ORDER_TRACKING_URL]' => $orderData['tracking_url'],
            '[RECOMMENDED_PRODUCTS]' => '', // You can add logic to generate recommended products if available
            '[DELIVERY_DATE]' => $orderData['delivery_date'],
            '[CUSTOMER_SERVICE_EMAIL]' => $orderData['customer_service_email'],
            '[COMPANY_ADDRESS]' => $orderData['company_address'],
            '[COMPANY_CONTACT]' => $orderData['company_contact'],
            '[CUSTOMER_EMAIL]' => $orderData['customer_email'],
            '[UNSUBSCRIBE_URL]' => $orderData['unsubscribe_url'],
            '[PAYMENT_STATUS]' => $orderData['payment_status'],
        ];

        foreach ($placeholders as $placeholder => $value) {
            $emailTemplate = str_replace($placeholder, $value, $emailTemplate);
        }

        // Generate product rows dynamically
        $productRows = '';
        foreach ($orderData['items'] as $item) {
            // var_dump($item);
            $productRows .= "
            <div class='product-row' style='margin-bottom: 15px;'>
                <img src='{$item['image_url']}' class='product-image' alt='{$item['name']}' style='width: 50px; height: 50px; margin-right: 10px; vertical-align: middle;'>
                <div style='display: inline-block; vertical-align: middle;'>
                    <strong>{$item['name']}</strong><br>
                    Quantity: {$item['quantity']}<br>
                    Price: LKR " . number_format($item['price'], 2) . "
                </div>
            </div>
        ";
        }

        $emailTemplate = str_replace('[ORDER_ITEMS]', $productRows, $emailTemplate);

        return $emailTemplate;
    }
}
