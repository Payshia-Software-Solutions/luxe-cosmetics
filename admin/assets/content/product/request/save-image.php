<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../../vendor/autoload.php';

use Symfony\Component\HttpClient\HttpClient;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable('../../../../');
$dotenv->load();

$image = $_FILES['product_image'] ?? null;
$prefix = $_POST['image_prefix'] ?? '';
$productId = $_POST['productId'] ?? '';
$loggedUser = $_POST['LoggedUser'] ?? '';

$errors = [];

// Validate image upload
if ($image && $image['error'] === UPLOAD_ERR_OK) {
    $file_name = $image['name'];
    $file_size = $image['size'];
    $file_tmp = $image['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_extensions = ["jpeg", "jpg", "png", "webp"];

    if (!in_array($file_ext, $allowed_extensions)) {
        $errors[] = "Extension not allowed. Please choose a JPEG, PNG, or WEBP file.";
    }
    if ($file_size > 2097152) {
        $errors[] = "File size must be 2 MB or less.";
    }

    if (empty($errors)) {
        // Create unique file name and directory
        $unique_file_name = uniqid($prefix . '_') . '.' . $file_ext;
        $directoryPath = "../../../../pos-system/assets/images/products/" . $productId;

        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0755, true);
        }
        $imagePath = $directoryPath . "/" . $unique_file_name;

        if (move_uploaded_file($file_tmp, $imagePath)) {
            // Proceed with API request
            $client = HttpClient::create();
            try {
                $response = $client->request('POST', $_ENV["SERVER_URL"] . '/product-images', [
                    'headers' => [
                        'Content-Type' => 'multipart/form-data',
                    ],
                    'body' => [
                        'product_id' => $productId,
                        'image_prefix' => $prefix,
                        'is_active' => 1,
                        'created_by' => $loggedUser,
                        'created_at' => date('Y-m-d H:i:s'),
                        'original_filename' => $unique_file_name,
                    ],
                ]);

                $statusCode = $response->getStatusCode();
                if ($statusCode === 201) {
                    echo json_encode(['status' => 'success', 'message' => 'Image and data uploaded successfully.']);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Failed to upload image. Server response: ' . $statusCode . " - " . $response->getContent(false),
                    ]);
                }
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => 'Request failed: ' . $e->getMessage()]);
            }
        } else {
            $errors[] = "Failed to move the uploaded file.";
        }
    }
} else {
    $errors[] = "Image upload error. Code: " . ($image['error'] ?? 'No image uploaded.');
}

if (!empty($errors)) {
    echo json_encode(['status' => 'error', 'message' => $errors[0]]);
}
