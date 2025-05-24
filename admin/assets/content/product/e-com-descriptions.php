<?php
$productId = $_POST['productId']; // Assuming productId is passed via POST

require_once '../../../vendor/autoload.php';

use Symfony\Component\HttpClient\HttpClient;

$client = HttpClient::create();
$dotenv = Dotenv\Dotenv::createImmutable('../../../');
$dotenv->load();

// Define the endpoint to fetch product data if available (for update)
$serverUrl = $_ENV['SERVER_URL'] . '/product-ecom-values/by-product/' . $productId;
$client = HttpClient::create();

$productData = [];
try {
    // Try to fetch product data only if the product ID is valid (for update)
    if (!empty($productId)) {
        $response = $client->request('GET', $serverUrl);
        $statusCode = $response->getStatusCode();

        if ($statusCode === 200) {
            $productData = $response->toArray(); // Decode the response into an associative array
            // var_dump($productData);
        } else {
            // Handle error if product data could not be fetched
            $dataError = json_encode(['status' => 'error', 'message' => 'Failed to fetch product data.']);
            // exit();
        }
    }
} catch (Exception $e) {
    // Handle any errors in the request
    echo json_encode(['status' => 'error', 'message' => 'Request failed: ' . $e->getMessage()]);
    exit();
}
?>

<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-dark" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3 class="mb-0">Product Images</h3>
            <p class="border-bottom pb-2">Please fill in all required fields.</p>
        </div>
    </div>

    <form id="product-info-form" action="#" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($productId) ?>">

        <!-- Gross Weight -->
        <div class="mb-3 row">
            <div class="col-lg-3">
                <label for="net_weight" class=" col-form-label">Net Weight</label>
                <input type="number" class="form-control" id="net_weight" name="net_weight"
                    value="<?= isset($productData['net_weight']) ? htmlspecialchars($productData['net_weight']) : '' ?>" required>
            </div>
            <div class="col-lg-3">
                <label for="gross_weight" class=" col-form-label">Gross Weight</label>
                <input type="number" class="form-control" id="gross_weight" name="gross_weight"
                    value="<?= isset($productData['gross_weight']) ? htmlspecialchars($productData['gross_weight']) : '' ?>" required>
            </div>


            <div class="col-lg-3">
                <label for="caffain_level" class="col-form-label">Caffeine Level</label>
                <select class="form-control" id="caffain_level" name="caffain_level" required>
                    <option value="Low" <?= isset($productData['caffain_level']) && $productData['caffain_level'] == "Low" ? "selected" : '' ?>>Low</option>
                    <option value="Medium" <?= isset($productData['caffain_level']) && $productData['caffain_level'] == "Medium" ? "selected" : '' ?>>Medium</option>
                    <option value="High" <?= isset($productData['caffain_level']) && $productData['caffain_level'] == "High" ? "selected" : '' ?>>High</option>
                </select>

            </div>
            <div class="col-lg-3">
                <label for="usage_type" class="col-form-label">Usage Time</label>
                <select class="form-control" id="usage_type" name="usage_type" required>
                    <option value="Morning" <?= isset($productData['usage_type']) && $productData['usage_type'] == "Morning" ? "selected" : '' ?>>Morning</option>
                    <option value="Afternoon" <?= isset($productData['usage_type']) && $productData['usage_type'] == "Afternoon" ? "selected" : '' ?>>Afternoon</option>
                    <option value="Evening" <?= isset($productData['usage_type']) && $productData['usage_type'] == "Evening" ? "selected" : '' ?>>Evening</option>
                    <option value="Night" <?= isset($productData['usage_type']) && $productData['usage_type'] == "Night" ? "selected" : '' ?>>Night</option>
                </select>

            </div>
        </div>

        <div class="mb-3 row">
            <div class="col-lg-3">
                <label for="product_type" class="col-form-label">Tea Type</label>
                <select class="form-control" name="product_type" id="product_type">
                    <option value="Tea Bags" <?= isset($productData['product_type']) && $productData['product_type'] == "Tea Bags" ? "selected" : '' ?>>
                        Tea Bags
                    </option>
                    <option value="Loose Tea" <?= isset($productData['product_type']) && $productData['product_type'] == "Loose Tea" ? "selected" : '' ?>>
                        Loose Tea
                    </option>
                </select>


            </div>
            <div class="col-lg-3">
                <label for="tb_count" class="col-form-label">Tea Bags</label>
                <input type="number" class="form-control" id="tb" name="tb_count"
                    value="<?= isset($productData['tb_count']) ? htmlspecialchars($productData['tb_count']) : '' ?>" required>

            </div>
            <div class="col-lg-3">
                <label for="serving_count" class="col-form-label">Serving Count</label>

                <input type="number" class="form-control" id="serving_count" name="serving_count"
                    value="<?= isset($productData['serving_count']) ? htmlspecialchars($productData['serving_count']) : '' ?>" required>

            </div>
            <div class="col-lg-3">
                <label for="per_pack_gram" class="col-form-label">Per Pack Gram</label>

                <input type="number" class="form-control" id="per_pack_gram" name="per_pack_gram"
                    value="<?= isset($productData['per_pack_gram']) ? htmlspecialchars($productData['per_pack_gram']) : '' ?>" required>

            </div>

        </div>



        <!-- Tasting Notes -->
        <div class="mb-3 row">
            <label for="tasting_notes" class="col-sm-3 col-form-label">Tasting Notes</label>
            <div class="col-sm-9">
                <textarea class="form-control" id="tasting_notes" name="tasting_notes" rows="3" required><?= isset($productData['tasting_notes']) ? htmlspecialchars($productData['tasting_notes']) : '' ?></textarea>
            </div>
        </div>

        <!-- Ingredients -->
        <div class="mb-3 row">
            <label for="ingredients" class="col-sm-3 col-form-label">Ingredients</label>
            <div class="col-sm-9">
                <textarea class="form-control" id="ingredients" name="ingredients" rows="3" required><?= isset($productData['ingredients']) ? htmlspecialchars($productData['ingredients']) : '' ?></textarea>
            </div>
        </div>

        <!-- Tea Grades -->
        <div class="mb-3 row">
            <label for="tea_grades" class="col-sm-3 col-form-label">Tea Grades</label>
            <div class="col-sm-9">
                <textarea class="form-control" id="tea_grades" name="tea_grades" rows="3" required><?= isset($productData['tea_grades']) ? htmlspecialchars($productData['tea_grades']) : '' ?></textarea>
            </div>
        </div>





        <!-- Brew Temperature -->
        <div class="mb-3 row">
            <label for="breaw_temp" class="col-sm-3 col-form-label">Brew Temperature (°C)</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="breaw_temp" name="breaw_temp"
                    value="<?= isset($productData['breaw_temp']) ? htmlspecialchars($productData['breaw_temp']) : '' ?>" required>
            </div>
        </div>


        <!-- Water Type -->
        <div class="mb-3 row">
            <label for="water_type" class="col-sm-3 col-form-label">Water Type</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="water_type" name="water_type"
                    value="<?= isset($productData['water_type']) ? htmlspecialchars($productData['water_type']) : '' ?>" required>
            </div>
        </div>

        <!-- Water -->
        <div class="mb-3 row">
            <label for="water" class="col-sm-3 col-form-label">Water (ml)</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="water" name="water"
                    value="<?= isset($productData['water']) ? htmlspecialchars($productData['water']) : '' ?>" required>
            </div>
        </div>

        <!-- Brew Duration -->
        <div class="mb-3 row">
            <label for="brew_duration" class="col-sm-3 col-form-label">Brew Duration (minutes)</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="brew_duration" name="brew_duration"
                    value="<?= isset($productData['brew_duration']) ? htmlspecialchars($productData['brew_duration']) : '' ?>" required>
            </div>
        </div>

        <!-- Detailed Description -->
        <div class="mb-3 row">
            <label for="detailed_description" class="col-sm-3 col-form-label">Detailed Description</label>
            <div class="col-sm-9">
                <textarea class="form-control" id="detailed_description" name="detailed_description" rows="3" required><?= isset($productData['detailed_description']) ? htmlspecialchars($productData['detailed_description']) : '' ?></textarea>
            </div>
        </div>

        <!-- How to Use -->
        <div class="mb-3 row">
            <label for="how_to_use" class="col-sm-3 col-form-label">How to Use</label>
            <div class="col-sm-9">
                <textarea class="form-control" id="how_to_use" name="how_to_use" rows="3" required><?= isset($productData['how_to_use']) ? htmlspecialchars($productData['how_to_use']) : '' ?></textarea>
            </div>
        </div>





        <!-- Submit Button -->
        <div class="mb-3 row">
            <div class="col-sm-12 text-center">
                <button type="button" onclick="SaveProductInfo('<?= $productId ?>')" class="btn btn-primary">Save Product</button>
            </div>
        </div>
    </form>
</div>