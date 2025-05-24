<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../../vendor/autoload.php';

// Load .env Configuration
$dotenv = Dotenv\Dotenv::createImmutable('../../../../');
$dotenv->load();

use Symfony\Component\HttpClient\HttpClient;

$client = HttpClient::create();

$response = $client->request('GET', $_ENV['SERVER_URL'] . '/promo_codes');
// Check if the response status code is 404
if ($response->getStatusCode() === 404) {
    // If 404, set $productImages as an empty array
    $promoCodes = [];
} else {
    // Otherwise, parse the response body as an array
    $promoCodes = $response->toArray();
}
$LoggedUser = $_POST['LoggedUser'];
?>

<div class="row mt-5">
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-file-contract icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Codes</p>
                <h1><?= count($promoCodes) ?></h1>
            </div>
        </div>
    </div>
    <?php
    $pageID = 15;
    $userPrivilege = GetUserPrivileges($link, $LoggedUser,  $pageID);

    if (!empty($userPrivilege)) {
        $readAccess = $userPrivilege[$LoggedUser]['read'];
        $writeAccess = $userPrivilege[$LoggedUser]['write'];
        $AllAccess = $userPrivilege[$LoggedUser]['all'];

        if ($writeAccess == 1) {
    ?>
            <div class="col-md-9 text-end mt-4 mt-md-0">
                <button class="btn btn-dark" type="button" onclick="NewCode()"><i class="fa-solid fa-plus"></i> Promo Code</button>
            </div>
    <?php
        }
    }
    ?>
</div>
<style>
    #order-table tr {
        height: auto !important
    }

    .recent-po-container {
        max-height: 70vh;
        overflow: auto;
    }
</style>

<div class="row mt-5">
    <div class="col-md-8">
        <div class="table-title font-weight-bold mb-4 mt-0">Promo Codes</div>

        <div class="row">
            <div class="col-12 mb-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body ">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="master-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Code</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Value</th>
                                        <th scope="col">Start Date</th>
                                        <th scope="col">End Date</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Max Usage</th>
                                        <th scope="col">Min Order</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($promoCodes as $promoCode) : ?>
                                        <tr>
                                            <td><?= $promoCode['code'] ?></td>
                                            <td><?= $promoCode['discount_type'] ?></td>
                                            <td><?= $promoCode['discount_value'] ?></td>
                                            <td><?= date('Y-m-d', strtotime($promoCode['start_date'])) ?></td>
                                            <td><?= date('Y-m-d', strtotime($promoCode['end_date'])) ?></td>
                                            <td>
                                                <span class="badge <?= $promoCode['is_active'] ? 'bg-primary' : 'bg-danger' ?>">
                                                    <?= $promoCode['is_active'] ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>

                                            <td><?= $promoCode['max_uses'] ?></td>
                                            <td><?= $promoCode['min_order_value'] ?></td>
                                            <td>
                                                <button onclick="NewCode('<?= $promoCode['id'] ?>')" class="btn btn-dark btn-sm" type="button">Edit</button>
                                                <button onclick="PromoApplicable('<?= $promoCode['id'] ?>')" class="btn btn-dark btn-sm" type="button">Products</button>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="row">

            <div class="col-12">
                <div class="table-title font-weight-bold mb-4 mt-0">Recent Saved</div>
            </div>

            <div class="recent-po-container"></div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#master-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
                // 'colvis'
            ],
            order: [
                [0, 'desc'],
                [3, 'desc']
            ]
        });
    });
</script>