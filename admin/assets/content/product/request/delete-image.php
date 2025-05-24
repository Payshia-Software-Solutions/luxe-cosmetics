<?php
require_once '../../../../vendor/autoload.php';

use Symfony\Component\HttpClient\HttpClient;

$client = HttpClient::create();
$dotenv = Dotenv\Dotenv::createImmutable('../../../../');
$dotenv->load();

$client = HttpClient::create();

// Gather necessary data from the form (or whatever source you're using)
$ImageId = $_POST['ImageId'];  // Product ID from the form

try {
    // Send a PUT request to update the product image status
    $response = $client->request('DELETE', $_ENV["SERVER_URL"] . '/product-images/' . $ImageId, [
        'headers' => [
            'Accept' => 'application/json', // Expect JSON response
        ]
    ]);

    // Handle server response
    $statusCode = $response->getStatusCode();
    if ($statusCode === 200) {
        // If the request was successful
        echo json_encode(['status' => 'success', 'message' => 'Product Deleted successfully.']);
    } else {
        // If the server responded with an error
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to update product status. Server response: ' . $response->getContent(false)
        ]);
    }
} catch (Exception $e) {
    // Handle any exceptions that occur during the request
    echo json_encode(['status' => 'error', 'message' => 'Request failed: ' . $e->getMessage()]);
}
