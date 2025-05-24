<?php
require_once '../../../../vendor/autoload.php';

// Load .env Configuration
$dotenv = Dotenv\Dotenv::createImmutable('../../../../');
$dotenv->load();

use Symfony\Component\HttpClient\HttpClient;

$client = HttpClient::create();

$promoCode = [];
$promoCodeId = $_POST['promoCodeId'];
try {
    $response = $client->request('GET', $_ENV['SERVER_URL'] . '/promo_codes/' . $promoCodeId);
    $statusCode = $response->getStatusCode();

    if ($statusCode === 200) {
        $promoCode = $response->toArray(); // Decode the response into an associative array
    } else {
        // Handle error if promo code data could not be fetched
        $dataError = json_encode(['status' => 'error', 'message' => 'Failed to fetch promo code data.']);
        exit();
    }
} catch (Exception $e) {
    // Handle any errors in the request
    echo json_encode(['status' => 'error', 'message' => 'Request failed: ' . $e->getMessage()]);
    exit();
}


// Get Product List
try {
    $response = $client->request('GET', $_ENV['SERVER_URL'] . '/products/');
    $statusCode = $response->getStatusCode();

    if ($statusCode === 200) {
        $productList = $response->toArray(); // Decode the response into an associative array
    } else {
        // Handle error if promo code data could not be fetched
        $dataError = json_encode(['status' => 'error', 'message' => 'Failed to fetch promo code data.']);
        exit();
    }
} catch (Exception $e) {
    // Handle any errors in the request
    echo json_encode(['status' => 'error', 'message' => 'Request failed: ' . $e->getMessage()]);
    exit();
}

$applicableProductList = [];
// Get Product List
try {
    $response = $client->request('GET', $_ENV['SERVER_URL'] . '/promo-code-products/get-by-promo-code/' . $promoCode['code']);
    $statusCode = $response->getStatusCode();

    if ($statusCode === 200) {
        $applicableProductList = $response->toArray(); // Decode the response into an associative array
    } else {
        // Handle error if promo code data could not be fetched
        $dataError = json_encode(['status' => 'error', 'message' => 'Failed to fetch promo code data.']);
        // exit();
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
            <button type="button" class="btn btn-sm btn-dark" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3 class="mb-0">Promo Code <?= $promoCode['code'] ?> | Applicable Products</h3>
            <p class="border-bottom pb-2">Please fill in all required fields.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="text-end">

                <button type="button" class="btn btn-sm btn-secondary mb-2" id="toggleCheckboxes">Check All</button>
            </div>

            <form action="#" method="post" id="promoProductForm">
                <div class="row">
                    <?php foreach ($productList as $product): ?>
                        <div class="col-md-4">
                            <div class="form-check">
                                <?php
                                // Check if the product is in the applicable product list and has a status of 1 (checked)
                                $isChecked = 'unchecked'; // Default is unchecked
                                foreach ($applicableProductList as $appliedProduct) {
                                    if ($appliedProduct['product_id'] == $product['product_id'] && $appliedProduct['status'] == 1) {
                                        $isChecked = 'checked'; // Set as checked if the status is 1
                                        break;
                                    }
                                }
                                ?>
                                <input class="form-check-input selected_products" type="checkbox" name="selected_products[]" value="<?= $product['product_id'] ?>" id="product_<?= $product['product_id'] ?>" <?= $isChecked ?>>
                                <label class="form-check-label" for="product_<?= $product['product_id'] ?>">
                                    <?= htmlspecialchars(trim($product['product_name'])) ?>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>


                </div>
                <button type="button" onclick="SavePromoCodeProducts('<?= $promoCode['code'] ?>')" class="btn btn-primary">Submit</button>
            </form>
        </div>

    </div>
</div>

<script>
    document.getElementById("toggleCheckboxes").addEventListener("click", function() {
        let checkboxes = document.querySelectorAll("input[name='selected_products[]']");
        let allChecked = [...checkboxes].every(checkbox => checkbox.checked);

        checkboxes.forEach(checkbox => checkbox.checked = !allChecked);

        this.textContent = allChecked ? "Check All" : "Uncheck All";
    });
</script>