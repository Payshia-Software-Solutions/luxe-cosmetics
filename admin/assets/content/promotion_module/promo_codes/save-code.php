<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../../vendor/autoload.php';

// Load .env Configuration
$dotenv = Dotenv\Dotenv::createImmutable('../../../../');
$dotenv->load();

use Symfony\Component\HttpClient\HttpClient;

// Initialize the Symfony HttpClient
$client = HttpClient::create();
// var_dump($_POST);
// Define the local server URL (replace with actual URL)

$promoCodeId = $_POST['promoCodeId'];
try {
    $response = $client->request('GET', $_ENV['SERVER_URL'] . '/promo_codes/' . $promoCodeId);
    $statusCode = $response->getStatusCode();

    if ($statusCode === 200) {
        // Product doesn't exist, perform a create (POST request)
        $updateResponse = $client->request('PUT', $_ENV["SERVER_URL"] . '/promo_codes/' . $promoCodeId, [
            'headers' => [
                'Content-Type' => 'application/json', // Send data as JSON
            ],
            'json' => $_POST, // Send form data as JSON
        ]);

        if ($updateResponse->getStatusCode() === 201) {
            echo json_encode(['status' => 'success', 'message' => 'Promo Code Updated successfully.']);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to update Promo Code. Server response: ' . $updateResponse->getStatusCode(),
            ]);
        }
    } else {
        // Product doesn't exist, perform a create (POST request)
        $createResponse = $client->request('POST', $_ENV["SERVER_URL"] . '/promo_codes/', [
            'headers' => [
                'Content-Type' => 'application/json', // Send data as JSON
            ],
            'json' => $_POST, // Send form data as JSON
        ]);

        if ($createResponse->getStatusCode() === 201) {
            echo json_encode(['status' => 'success', 'message' => 'Promo Code created successfully.']);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to create Promo Code. Server response: ' . $createResponse->getStatusCode(),
            ]);
        }
    }
} catch (Exception $e) {
    // Handle any errors in the request
    echo json_encode(['status' => 'error', 'message' => 'Request failed: ' . $e->getMessage()]);
    exit();
}
