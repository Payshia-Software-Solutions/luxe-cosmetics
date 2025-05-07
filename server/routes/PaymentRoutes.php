<?php

require_once './controllers/PaymentController.php';  // Include the controller

// Instantiate the controller with PDO instance
$pdo = $GLOBALS['pdo'];
$paymentController = new PaymentController($pdo);

// Define payment-related routes
return [
    // Route to initiate payment and redirect to PayHere checkout
    'POST /payment/initiate-payment' => function () use ($paymentController) {
        $paymentController->initiatePayment();
    },

    // Route to initiate payment and redirect to PayHere checkout
    'POST /payment/initiate-cod-invoice' => function () use ($paymentController) {
        $paymentController->initiateCodInvoice();
    },

    // Route to handle payment notification callback from PayHere
    'GET /payment/success' => function () use ($paymentController) {
        $paymentController->paymentReturn();
    },

    // Route to handle payment notification callback from PayHere
    'POST /payment/notify' => function () use ($paymentController) {
        $paymentController->paymentNotify();
    },

    // Route to handle payment notification callback from PayHere
    'POST /payment/send-invoice-email/{invoice_number}' => function ($invoice_number) use ($paymentController) {
        $paymentController->SendInvoiceEmail($invoice_number);
    },


    // Route to handle payment notification callback from PayHere
    'POST /payment/sent-order-confirmation' => function () use ($paymentController) {
        // Parse the request body (expected to be in JSON format)
        $input = json_decode(file_get_contents('php://input'), true);

        // Validate the input
        if (isset($input['orderData']) && isset($input['customer_email'])) {
            $orderData = $input['orderData'];
            $customerEmail = $input['customer_email'];

            // Call the controller method to send the order confirmation email
            $result = $paymentController->sendOrderConfirmationEmail($orderData, $customerEmail);

            // Return response based on the result
            if ($result) {
                http_response_code(200);
                echo json_encode(['status' => 'success', 'message' => 'Email sent successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to send email']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        }
    },

    // Route to handle return after payment (success or failure)
    'GET /payment/return' => function () use ($paymentController) {
        $paymentController->paymentReturn();
    }
];
