<?php
require_once './models/MasterProductImages.php';

class MasterProductImagesController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new MasterProductImages($pdo);
    }

    // Get all images
    public function getAllImages()
    {
        $images = $this->model->getAllImages();
        echo json_encode($images);
    }

    // Get a single image by ID
    public function getImageById($id)
    {
        $image = $this->model->getImageById($id);
        if ($image) {
            echo json_encode($image);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Image not found']);
        }
    }

    public function getImageByProductId($id)
    {
        $image = $this->model->getImageByProductId($id);
        if ($image) {
            echo json_encode($image);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Image not found']);
        }
    }

    public function getImageByProductIdAdmin($id)
    {
        $image = $this->model->getImageByProductIdAdmin($id);
        if ($image) {
            echo json_encode($image);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Image not found']);
        }
    }

    // Create a new product image
    public function createImage()
    {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $productId = $_POST['product_id'];
            $isActive = $_POST['is_active'];
            $createdBy = $_POST['created_by'];
            $createdAt = $_POST['created_at'];
            $original_filename = $_POST['original_filename'];
            echo $original_filename;

            // Save the file to a directory
            $uploadDir = './uploads/images/product-images/' . $productId . '/';
            if (!file_exists($uploadDir)) {
                // Create the directory if it doesn't exist
                mkdir($uploadDir, 0777, true); // 0777 permissions to allow full read/write
            }

            $newFileName = $original_filename;
            $targetPath = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                // Save data into database using the `createImage` method
                $this->model->createImage([
                    'product_id' => $productId,
                    'image_path' => $newFileName,
                    'is_active' => $isActive,
                    'created_by' => $createdBy,
                    'created_at' => $createdAt,
                ]);

                http_response_code(201);
                echo json_encode(['message' => 'Image created successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to save the image file']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request or missing fields']);
        }
    }

    public function createImageNew()
    {

        $data = json_decode(file_get_contents("php://input"), true);
        // Check if required fields are present in the request
        if (isset($_POST['product_id'], $_POST['is_active'], $_POST['created_by'], $_POST['created_at'], $_POST['original_filename'], $_POST['image_prefix'])) {
            $productId = $_POST['product_id'];
            $isActive = $_POST['is_active'];
            $createdBy = $_POST['created_by'];
            $createdAt = $_POST['created_at'];
            $original_filename = $_POST['original_filename'];
            $image_prefix = $_POST['image_prefix'];

            // Save data into database using the `createImage` method
            $this->model->createImage([
                'image_prefix' => $image_prefix,
                'product_id' => $productId,
                'image_path' => $original_filename,
                'is_active' => $isActive,
                'created_by' => $createdBy,
                'created_at' => $createdAt,
            ]);

            http_response_code(201);
            echo json_encode(['message' => 'Image record created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request or missing fields']);
        }
    }




    // Update an existing product image
    public function updateImage($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['product_id'], $data['image_path'], $data['is_active'], $data['created_by'], $data['created_at'])) {
            $this->model->updateImage($id, $data);
            echo json_encode(['message' => 'Image updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Change the 'is_active' status of a product image
    public function changeImageStatus($id)
    {
        // Get data from the request body (assuming 'is_active' is passed)
        $data = json_decode(file_get_contents("php://input"), true);

        if ($data && isset($data['is_active'])) {
            // Update only the 'is_active' field
            $this->model->updateImageStatus($id, $data['is_active']);

            echo json_encode(['message' => 'Image status updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input. "is_active" is required.']);
        }
    }


    // Delete a product image
    public function deleteImage($id)
    {
        $this->model->deleteImage($id);
        echo json_encode(['message' => 'Image deleted successfully']);
    }
}
