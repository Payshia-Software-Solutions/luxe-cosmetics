<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Include autoload file for Symfony HttpClient
require_once '../../../../vendor/autoload.php';

use Symfony\Component\HttpClient\HttpClient;

$dotenv = Dotenv\Dotenv::createImmutable('../../../../');
$dotenv->load();

// Initialize variables from the form data
$productId = $_POST['product_id']; // Product ID from form
$grossWeight = $_POST['gross_weight']; // Gross Weight from form
$netWeight = $_POST['net_weight']; // Net Weight from form
$tastingNotes = $_POST['tasting_notes']; // Tasting Notes from form
$ingredients = $_POST['ingredients']; // Ingredients from form
$teaGrades = $_POST['tea_grades']; // Tea Grades from form
$caffeineLevel = $_POST['caffain_level']; // Caffeine Level from form
$brewTemp = $_POST['breaw_temp']; // Brew Temperature from form
$usageType = $_POST['usage_type']; // Usage Type from form
$waterType = $_POST['water_type']; // Water Type from form
$water = $_POST['water']; // Water Amount from form
$brewDuration = $_POST['brew_duration']; // Brew Duration from form
$detailedDescription = $_POST['detailed_description']; // Detailed Description from form
$howToUse = $_POST['how_to_use']; // How to Use from form
$loggedUser = $_POST['LoggedUser']; // Logged-in user
$product_type = $_POST['product_type']; // Brew Duration from form
$tb_count = $_POST['tb_count']; // Detailed Description from form
$serving_count = $_POST['serving_count']; // How to Use from form
$per_pack_gram = $_POST['per_pack_gram']; // Logged-in user

// Prepare the data to be sent in the request
$data = [
    'product_id' => $productId,
    'gross_weight' => $grossWeight,
    'net_weight' => $netWeight,
    'tasting_notes' => $tastingNotes,
    'ingredients' => $ingredients,
    'tea_grades' => $teaGrades,
    'caffain_level' => $caffeineLevel,
    'breaw_temp' => $brewTemp,
    'usage_type' => $usageType,
    'water_type' => $waterType,
    'water' => $water,
    'brew_duration' => $brewDuration,
    'detailed_description' => $detailedDescription,
    'how_to_use' => $howToUse,
    'is_active' => 1,                       // Active status
    'created_by' => $loggedUser,            // Created by (Logged-in user)
    'created_at' => date('Y-m-d H:i:s'),    // Current timestamp
    'product_type' => $product_type,
    'tb_count' => $tb_count,
    'serving_count' => $serving_count,
    'per_pack_gram' => $per_pack_gram,
];

// Initialize the Symfony HttpClient
$client = HttpClient::create();

// Define the local server URL (replace with actual URL)

try {
    // Check if the product exists for update or create
    $productExistsUrl = $_ENV["SERVER_URL"] . '/product-ecom-values/by-product/' . $productId;
    $response = $client->request('GET', $productExistsUrl);

    // var_dump($response->getStatusCode());

    if ($response->getStatusCode() === 200) {
        // Product exists, perform an update (PUT request)
        $updateResponse = $client->request('PUT', $_ENV["SERVER_URL"] . '/product-ecom-values/by-product/' . $productId, [
            'headers' => [
                'Content-Type' => 'application/json', // Send data as JSON
            ],
            'json' => $data, // Send form data as JSON
        ]);

        if ($updateResponse->getStatusCode() === 200) {
            echo json_encode(['status' => 'success', 'message' => 'Product updated successfully.']);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to update product. Server response: ' . $updateResponse->getStatusCode(),
            ]);
        }
    } else {
        // Product doesn't exist, perform a create (POST request)
        $createResponse = $client->request('POST', $_ENV["SERVER_URL"] . '/product-ecom-values/', [
            'headers' => [
                'Content-Type' => 'application/json', // Send data as JSON
            ],
            'json' => $data, // Send form data as JSON
        ]);

        if ($createResponse->getStatusCode() === 201) {
            echo json_encode(['status' => 'success', 'message' => 'Product created successfully.']);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to create product. Server response: ' . $createResponse->getStatusCode(),
            ]);
        }
    }
} catch (Exception $e) {
    // Handle any errors during the request
    echo json_encode(['status' => 'error', 'message' => 'Request failed: ' . $e->getMessage()]);
}
