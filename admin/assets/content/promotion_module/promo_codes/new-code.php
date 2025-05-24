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
?>

<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button type="button" class="btn btn-sm btn-dark" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3 class="mb-0">Promo Code</h3>
            <p class="border-bottom pb-2">Please fill in all required fields.</p>
        </div>
    </div>

    <!-- Form Start -->
    <form id="promoCodeForm" action="#" method="POST">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="status" class="form-label">Type</label>
                <select class="form-select" id="discount_type" name="discount_type" required>
                    <option value="percentage" <?= (isset($promoCode['discount_type']) && $promoCode['discount_type'] === 'percentage') ? 'selected' : '' ?>>Percentage</option>
                    <option value="fixed" <?= (isset($promoCode['discount_type']) && $promoCode['discount_type'] === 'fixed') ? 'selected' : '' ?>>Fixed</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="code" class="form-label">Promo Code</label>
                <input value="<?= isset($promoCode['code']) ? htmlspecialchars($promoCode['code']) : '' ?>" type="text" class="form-control" id="code" name="code" placeholder="Enter promo code" required>
            </div>
            <div class="col-md-4 mb-3">
                <label for="discount_value" class="form-label">Discount Value</label>
                <input value="<?= isset($promoCode['discount_value']) ? htmlspecialchars($promoCode['discount_value']) : '' ?>" type="number" class="form-control" id="discount_value" name="discount_value" placeholder="Enter discount value" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="start_date" class="form-label">Start Date</label><input value="<?= isset($promoCode['start_date']) ? (new DateTime($promoCode['start_date']))->format('Y-m-d') : '' ?>" type="date" class="form-control" id="start_date" name="start_date" required>

            </div>
            <div class="col-md-6 mb-3">
                <label for="end_date" class="form-label">End Date</label>
                <input value="<?= isset($promoCode['end_date']) ? (new DateTime($promoCode['end_date']))->format('Y-m-d') : '' ?>" type="date" class="form-control" id="end_date" name="end_date" required>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter promo code description"><?= isset($promoCode['description']) ? htmlspecialchars($promoCode['description']) : '' ?></textarea>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="is_active" name="is_active" required>
                    <option value="1" <?= (isset($promoCode['is_active']) && $promoCode['is_active'] == 1) ? 'selected' : '' ?>>Active</option>
                    <option value="0" <?= (isset($promoCode['is_active']) && $promoCode['is_active'] == 0) ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="max_uses" class="form-label">Max Usage</label>
                <input value="<?= isset($promoCode['max_uses']) ? htmlspecialchars($promoCode['max_uses']) : '' ?>" type="number" class="form-control" id="max_uses" name="max_uses" required>
            </div>
            <div class="col-md-4 mb-3">
                <label for="min_order_value" class="form-label">Min Order</label>
                <input value="<?= isset($promoCode['min_order_value']) ? htmlspecialchars($promoCode['min_order_value']) : '' ?>" type="number" class="form-control" id="min_order_value" name="min_order_value" required>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-end">
                <button onclick="SaveNewCode('<?= $promoCodeId ?>')" type="button" class="btn btn-dark">Save</button>
            </div>
        </div>
    </form>
    <!-- Form End -->
</div>