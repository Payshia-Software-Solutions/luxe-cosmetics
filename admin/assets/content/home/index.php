<?php
require_once '../../../vendor/autoload.php';

use Symfony\Component\HttpClient\HttpClient;

$client = HttpClient::create();
$dotenv = Dotenv\Dotenv::createImmutable('../../../');
$dotenv->load();

$client = HttpClient::create();
require_once('../../../include/config.php');
include '../../../include/function-update.php';
include '../../../include/reporting-functions.php';
include '../../../include/finance-functions.php';
include '../../../include/settings_functions.php';

$UserLevel = $_POST['UserLevel'];
$StudentNumber = $_POST['LoggedUser'];

// Get today's date
$today = date('Y-m-d');
$dayBefore = (new DateTime($today))->modify('-1 day')->format('Y-m-d');
$currentDate = date('Y-m-d');
$firstDayOfCurrentMonth = date('Y-m-01');
$firstDayOfLastMonth = date('Y-m-01', strtotime('-1 month'));
$lastDayOfLastMonth = date('Y-m-t', strtotime('-1 month')); // Last day of the previous month
$ClassesCount = $TutorCount = $UsersCount = $ClassesCount = 0;

$Locations = GetLocations($link);
$defaultLocation = GetUserDefaultValue($link, $StudentNumber, 'defaultLocation');
$default_location_name = $Locations[$defaultLocation]['location_name'];
?>
<style>
    .location-title {
        font-weight: 700;
    }

    #date-time {
        font-size: 20px;
    }
</style>

<div class="row mt-5">

    <div class="col-md-6 col-lg-3 d-flex">
        <?php


        // Day Sale Card
        // Fetch sales data for today and the day before
        $todaySale = getInvoicesByDateAllByLocation($link, $today, $defaultLocation);
        $dayBeforeSale = getInvoicesByDateAllByLocation($link, $dayBefore, $defaultLocation);

        // Ensure both values are numeric for comparison
        $todaySale = is_numeric($todaySale) ? $todaySale : 0;
        $dayBeforeSale = is_numeric($dayBeforeSale) ? $dayBeforeSale : 0;

        // Initialize variables for comparison
        $comparisonText = "No change";
        $iconClass = "fa-arrows-alt-h text-muted"; // Neutral icon and color
        $badgeClass = "bg-secondary";
        $percentageChange = 0;

        // Avoid division by zero
        if ($dayBeforeSale > 0) {
            $percentageChange = (($todaySale - $dayBeforeSale) / $dayBeforeSale) * 100;
            if ($todaySale > $dayBeforeSale) {
                $comparisonText = number_format($percentageChange, 2) . "%" . " (" . formatAccountBalance($dayBeforeSale) . ")";
                $iconClass = "fa-arrow-up text-success"; // Up icon
                $badgeClass = "bg-success";
            } elseif ($todaySale < $dayBeforeSale) {
                $comparisonText = number_format(abs($percentageChange), 2) . "%" . " (" . formatAccountBalance($dayBeforeSale) . ")";
                $iconClass = "fa-arrow-down text-danger"; // Down icon
                $badgeClass = "bg-danger";
            }
        } elseif ($todaySale > 0) {
            $comparisonText = "Zero Sales yesterday";
            $iconClass = "fa-arrow-up text-success";
            $badgeClass = "bg-success";
        }
        // End of Day Sale Card
        ?>
        <div class="card item-card flex-fill">
            <div class="overlay-box">
                <i class="fa-solid fa-calendar-day icon-card"></i>
            </div>
            <div class="card-body">
                <p>Today Sales</p>
                <h1><?= formatAccountBalance($todaySale) ?></h1>
                <p class="mb-0 text-muted">
                    <i class="fa-solid <?= $iconClass ?>"></i> <?= $comparisonText ?>
                </p>
                <div class="badge <?= $badgeClass ?>"><?= $today ?></div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 d-flex">
        <?php
        // Receipts
        // Fetch receipts data for today and the day before
        $todayReceipts = getReceiptsByDateAllByLocation($link, $today, $defaultLocation);
        $dayBeforeReceipts = getReceiptsByDateAllByLocation($link, $dayBefore, $defaultLocation);

        // Ensure both values are numeric for comparison
        $todayReceipts = is_numeric($todayReceipts) ? $todayReceipts : 0;
        $dayBeforeReceipts = is_numeric($dayBeforeReceipts) ? $dayBeforeReceipts : 0;

        // Initialize variables for comparison
        $receiptsComparisonText = "No change";
        $receiptsIconClass = "fa-arrows-alt-h text-muted"; // Neutral icon and color
        $receiptsBadgeClass = "bg-secondary";
        $receiptsPercentageChange = 0;

        // Avoid division by zero
        if ($dayBeforeReceipts > 0) {
            $receiptsPercentageChange = (($todayReceipts - $dayBeforeReceipts) / $dayBeforeReceipts) * 100;
            if ($todayReceipts > $dayBeforeReceipts) {
                $receiptsComparisonText = number_format($receiptsPercentageChange, 2) . "%" . " (" . formatAccountBalance($dayBeforeReceipts) . ")";
                $receiptsIconClass = "fa-arrow-up text-success"; // Up icon
                $receiptsBadgeClass = "bg-success";
            } elseif ($todayReceipts < $dayBeforeReceipts) {
                $receiptsComparisonText = number_format(abs($receiptsPercentageChange), 2) . "%" . " (" . formatAccountBalance($dayBeforeReceipts) . ")";
                $receiptsIconClass = "fa-arrow-down text-danger"; // Down icon
                $receiptsBadgeClass = "bg-danger";
            }
        } elseif ($todayReceipts > 0) {
            $receiptsComparisonText = "Zero Receipts yesterday";
            $receiptsIconClass = "fa-arrow-up text-success";
            $receiptsBadgeClass = "bg-success";
        } else {
            $receiptsComparisonText = "No change";
            $receiptsIconClass = "fa-arrows-alt-h text-muted";
            $receiptsBadgeClass = "bg-secondary";
        }
        // End of Receipts
        ?>

        <div class="card item-card flex-fill">
            <div class="overlay-box">
                <i class="fa-solid fa-receipt icon-card"></i>
            </div>
            <div class="card-body">
                <p>Today Receipts</p>
                <h1><?= formatAccountBalance($todayReceipts) ?></h1>
                <p class="mb-0 text-muted">
                    <i class="fa-solid <?= $receiptsIconClass ?>"></i> <?= $receiptsComparisonText ?>
                </p>
                <div class="badge <?= $receiptsBadgeClass ?>"><?= $today ?></div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 d-flex">
        <?php
        // Get today's date and calculate the date ranges for the last two 7-day periods
        $currentDate = date('Y-m-d');
        $sevenDaysAgoDate = date('Y-m-d', strtotime('-7 days'));
        $fourteenDaysAgoDate = date('Y-m-d', strtotime('-14 days'));

        // Fetch sales data for the last 7 days and the 7 days before that
        $lastSevenDaysSales = getInvoicesTotalByDateRangeAll($link, $sevenDaysAgoDate, $currentDate, $defaultLocation);
        $previousSevenDaysSales = getInvoicesTotalByDateRangeAll($link, $fourteenDaysAgoDate, $sevenDaysAgoDate, $defaultLocation);

        // Ensure both values are numeric for comparison
        $lastSevenDaysSales = is_numeric($lastSevenDaysSales) ? $lastSevenDaysSales : 0;
        $previousSevenDaysSales = is_numeric($previousSevenDaysSales) ? $previousSevenDaysSales : 0;

        // Initialize variables for comparison
        $salesComparisonText = "No change";
        $salesIconClass = "fa-arrows-alt-h text-muted"; // Default icon
        $salesBadgeClass = "bg-secondary";
        $percentageDifference = 0;

        // Avoid division by zero
        if ($previousSevenDaysSales > 0) {
            $percentageChange = (($lastSevenDaysSales - $previousSevenDaysSales) / $previousSevenDaysSales) * 100;

            // Handle comparison text and icon based on percentage change
            if ($lastSevenDaysSales > $previousSevenDaysSales) {
                $salesComparisonText = number_format($percentageChange, 2) . "%" . " (" . formatAccountBalance($previousSevenDaysSales) . ")";
                $salesIconClass = "fa-arrow-up text-success"; // Up icon for increase
                $salesBadgeClass = "bg-success"; // Green badge for increase
            } elseif ($lastSevenDaysSales < $previousSevenDaysSales) {
                $salesComparisonText = number_format(abs($percentageChange), 2) . "%" . " (" . formatAccountBalance($previousSevenDaysSales) . ")";
                $salesIconClass = "fa-arrow-down text-danger"; // Down icon for decrease
                $salesBadgeClass = "bg-danger"; // Red badge for decrease
            }
        } elseif ($lastSevenDaysSales > 0) {
            $salesComparisonText = "Zero sales in the previous 7 days";
            $salesIconClass = "fa-arrow-up text-success"; // Up icon since there's an increase from zero
            $salesBadgeClass = "bg-success"; // Green badge for increase
        }
        ?>
        <div class="card item-card flex-fill">
            <div class="overlay-box">
                <i class="fa-solid fa-calendar-week icon-card"></i>
            </div>
            <div class="card-body">
                <p>Last 7 Days Sales</p>
                <h1><?= formatAccountBalance($lastSevenDaysSales) ?></h1>
                <p class="mb-0 text-muted">
                    <i class="fa-solid <?= $salesIconClass ?>"></i> <?= $salesComparisonText ?>
                </p>
                <div class="badge <?= $salesBadgeClass ?>"><?= $sevenDaysAgoDate ?> to <?= $today ?></div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 d-flex">
        <?php

        // Fetch sales data for this month and last month
        $thisMonthSales = getInvoicesTotalByDateRangeAll($link, $firstDayOfCurrentMonth, $currentDate, $defaultLocation);
        $lastMonthSales = getInvoicesTotalByDateRangeAll($link, $firstDayOfLastMonth, $lastDayOfLastMonth, $defaultLocation);

        // Ensure both values are numeric for comparison
        $thisMonthSales = is_numeric($thisMonthSales) ? $thisMonthSales : 0;
        $lastMonthSales = is_numeric($lastMonthSales) ? $lastMonthSales : 0;

        // Initialize variables for comparison
        $salesComparisonText = "No change";
        $salesIconClass = "fa-arrows-alt-h text-muted"; // Default icon
        $salesBadgeClass = "bg-secondary";
        $percentageDifference = 0;

        // Get full month name for display
        $currentMonthName = date('F', strtotime($firstDayOfCurrentMonth)); // Full month name for this month
        $lastMonthName = date('F', strtotime($firstDayOfLastMonth)); // Full month name for last month

        // Avoid division by zero
        if ($lastMonthSales > 0) {
            $percentageChange = (($thisMonthSales - $lastMonthSales) / $lastMonthSales) * 100;

            // Handle comparison text and icon based on percentage change
            if ($thisMonthSales > $lastMonthSales) {
                $salesComparisonText = number_format($percentageChange, 2) . "%" . " (" . formatAccountBalance($lastMonthSales) . ")";
                $salesIconClass = "fa-arrow-up text-success"; // Up icon for increase
                $salesBadgeClass = "bg-success"; // Green badge for increase
            } elseif ($thisMonthSales < $lastMonthSales) {
                $salesComparisonText = number_format(abs($percentageChange), 2) . "%" . " (" . formatAccountBalance($lastMonthSales) . ")";
                $salesIconClass = "fa-arrow-down text-danger"; // Down icon for decrease
                $salesBadgeClass = "bg-danger"; // Red badge for decrease
            }
        } elseif ($thisMonthSales > 0) {
            $salesComparisonText = "Zero sales in the " . $lastMonthName;
            $salesIconClass = "fa-arrow-up text-success"; // Up icon since there's an increase from zero
            $salesBadgeClass = "bg-success"; // Green badge for increase
        }

        ?>

        <div class="card item-card flex-fill">
            <div class="overlay-box">
                <i class="fa-solid fa-calendar-alt icon-card"></i>
            </div>
            <div class="card-body">
                <p>This Month's Sales</p>
                <h1><?= formatAccountBalance($thisMonthSales) ?></h1>
                <p class="mb-0 text-muted">
                    <i class="fa-solid <?= $salesIconClass ?>"></i> <?= $salesComparisonText ?>
                </p>
                <div class="badge <?= $salesBadgeClass ?>"><?= $currentMonthName ?></div>
            </div>
        </div>
    </div>

</div>


<div class="row mt-0 mt-md-5">
    <div class="col-md-6 col-lg-3 d-flex">
        <?php
        // Fetch sales data for today and the day before
        $todayCODSale = getInvoicesByDateAllByType($link, $today, "COD", $defaultLocation);
        $dayBeforeCODSale =  getInvoicesByDateAllByType($link, $dayBefore, "COD", $defaultLocation);

        // Ensure both values are numeric for comparison
        $todayCODSale = is_numeric($todayCODSale) ? $todayCODSale : 0;
        $dayBeforeCODSale = is_numeric($dayBeforeCODSale) ? $dayBeforeCODSale : 0;

        // Initialize variables for comparison
        $comparisonText = "No change";
        $iconClass = "fa-arrows-alt-h text-muted"; // Neutral icon and color
        $badgeClass = "bg-secondary";
        $percentageChange = 0;

        // Avoid division by zero
        if ($dayBeforeCODSale > 0) {
            $percentageChange = (($todayCODSale - $dayBeforeCODSale) / $dayBeforeCODSale) * 100;
            if ($todayCODSale > $dayBeforeCODSale) {
                $comparisonText = number_format($percentageChange, 2) . "%" . " (" . formatAccountBalance($dayBeforeCODSale) . ")";
                $iconClass = "fa-arrow-up text-success"; // Up icon
                $badgeClass = "bg-success";
            } elseif ($todayCODSale < $dayBeforeCODSale) {
                $comparisonText = number_format(abs($percentageChange), 2) . "%" . " (" . formatAccountBalance($dayBeforeCODSale) . ")";
                $iconClass = "fa-arrow-down text-danger"; // Down icon
                $badgeClass = "bg-danger";
            }
        } elseif ($todayCODSale > 0) {
            $comparisonText = "Zero COD Sales yesterday";
            $iconClass = "fa-arrow-up text-success";
            $badgeClass = "bg-success";
        }
        // End of Day Sale Card
        ?>
        <div class="card item-card flex-fill">
            <div class="overlay-box">
                <i class="fa-solid fa-money-bill-wave icon-card"></i>
            </div>
            <div class="card-body">
                <p>Today COD Sales</p>
                <h1><?= formatAccountBalance($todayCODSale) ?></h1>
                <p class="mb-0 text-muted">
                    <i class="fa-solid <?= $iconClass ?>"></i> <?= $comparisonText ?>
                </p>
                <div class="badge <?= $badgeClass ?>"><?= $today ?></div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 d-flex">
        <?php
        // Fetch sales data for today and the day before
        $todayPaidSale = getInvoicesByDateAllByType($link, $today, "Paid", $defaultLocation);
        $dayBeforePaidSale =  getInvoicesByDateAllByType($link, $dayBefore, "Paid", $defaultLocation);

        // Ensure both values are numeric for comparison
        $todayPaidSale = is_numeric($todayPaidSale) ? $todayPaidSale : 0;
        $dayBeforePaidSale = is_numeric($dayBeforePaidSale) ? $dayBeforePaidSale : 0;

        // Initialize variables for comparison
        $comparisonText = "No change";
        $iconClass = "fa-arrows-alt-h text-muted"; // Neutral icon and color
        $badgeClass = "bg-secondary";
        $percentageChange = 0;

        // Avoid division by zero
        if ($dayBeforePaidSale > 0) {
            $percentageChange = (($todayPaidSale - $dayBeforePaidSale) / $dayBeforePaidSale) * 100;
            if ($todayPaidSale > $dayBeforePaidSale) {
                $comparisonText = number_format($percentageChange, 2) . "%" . " (" . formatAccountBalance($dayBeforePaidSale) . ")";
                $iconClass = "fa-arrow-up text-success"; // Up icon
                $badgeClass = "bg-success";
            } elseif ($todayPaidSale < $dayBeforePaidSale) {
                $comparisonText = number_format(abs($percentageChange), 2) . "%" . " (" . formatAccountBalance($dayBeforePaidSale) . ")";
                $iconClass = "fa-arrow-down text-danger"; // Down icon
                $badgeClass = "bg-danger";
            }
        } elseif ($todayPaidSale > 0) {
            $comparisonText = "Zero Card Sales yesterday";
            $iconClass = "fa-arrow-up text-success";
            $badgeClass = "bg-success";
        }
        // End of Day Sale Card
        ?>
        <div class="card item-card flex-fill">
            <div class="overlay-box">
                <i class="fa-solid fa-credit-card icon-card"></i>
            </div>
            <div class="card-body">
                <p>Today Visa/Master Sales</p>
                <h1><?= formatAccountBalance($todayPaidSale) ?></h1>
                <p class="mb-0 text-muted">
                    <i class="fa-solid <?= $iconClass ?>"></i> <?= $comparisonText ?>
                </p>
                <div class="badge <?= $badgeClass ?>"><?= $today ?></div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 d-flex">
        <?php
        // Fetch sales data for today and the day before
        $totalCreditSale = getCreditInvoicesTotalByDateRangeAll($link, $defaultLocation);
        $todayCreditSale = getTotalCreditSalesByDateRange($link, $currentDate, $currentDate, $defaultLocation);

        // Initialize variables for comparison
        $comparisonText = "No change";
        $iconClass = "fa-arrows-alt-h text-muted"; // Neutral icon and color
        $badgeClass = "bg-secondary";
        $percentageChange = 0;

        // Ensure there is no division by zero when calculating percentage change
        if ($totalCreditSale > 0) {
            // Calculate percentage change
            $percentageChange = (($todayCreditSale) / $totalCreditSale) * 100;

            // Format the comparison text
            if ($percentageChange > 0) {
                $comparisonText = number_format($percentageChange, 2) . "%" . " (" . formatAccountBalance($todayCreditSale) . ")";
                $iconClass = "fa-arrow-up text-success"; // Upward arrow for positive change
                $badgeClass = "bg-danger"; // Green badge for positive change
            } elseif ($percentageChange < 0) {
                $comparisonText = number_format(abs($percentageChange), 2) . "%" . " (" . formatAccountBalance($todayCreditSale) . ")";
                $iconClass = "fa-arrow-down text-danger"; // Downward arrow for negative change
                $badgeClass = "bg-danger"; // Red badge for negative change
            } else {
                $comparisonText = "No change";
                $iconClass = "fa-arrows-alt-h text-muted"; // Neutral icon
                $badgeClass = "bg-secondary"; // Neutral badge
            }
        }
        // End of Day Sale Card
        ?>
        <div class="card item-card flex-fill">
            <div class="overlay-box">
                <i class="fa-solid fa-hand-holding-dollar icon-card"></i>
            </div>
            <div class="card-body">
                <p>Total Credit Sales</p>
                <h1><?= formatAccountBalance($totalCreditSale) ?></h1>
                <p class="mb-0 text-muted">
                    <i class="fa-solid <?= $iconClass ?>"></i> <?= number_format(abs($percentageChange), 2) ?>% (<?= formatAccountBalance($todayCreditSale) ?>)
                </p>
                <div class="badge <?= $badgeClass ?>">Up to <?= $currentDate ?></div>
            </div>
        </div>

    </div>
</div>

<!-- Hr -->
<div class="row">
    <div class="col-12">
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6 col-lg-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="table-title font-weight-bold mb-4 mt-0">Hourly Sales - <?= $default_location_name ?> | <?= $today ?></div>
                        <?php include './component/hourly-sales.php' ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="table-title font-weight-bold mb-4 mt-0">Sale Analysis - <?= $default_location_name ?> | <?= $today ?></div>
                        <?php include './component/sales-analysis.php' ?>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="table-title font-weight-bold mb-4 mt-0">Top 6 Fast-Moving Products by Revenue | <?= $currentMonthName ?> </div>
                        <?php include './component/by-revenue-top-10-fast-moving-products.php' ?>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <?php include './component/day-by-day-sale.php' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-body">
                <p class="text-secondary mb-0">Default Location</p>
                <h3 class="location-title mb-0 border-bottom pb-2"><?= $default_location_name ?></h3>
                <div id="date-time"></div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body text-center">

                <div class="table-title font-weight-bold mb-4 mt-0">Login to POS</div>
                <div class="row">
                    <div class="col-12 text-end">
                        <a href="./pos-system" target="_blank">
                            <button class="btn btn-light p-3 rounded-4 shadow-sm">
                                <img src="./pos-system/assets/images/pos-logo.png" class="w-25 pb-2">
                            </button>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="table-title font-weight-bold mb-4 mt-0">Top 10 Fast-Moving Products by Quantity | <?= $currentMonthName ?></div>
                <?php include './component/top-10-fast-moving-products.php' ?>
            </div>
        </div>
    </div>
</div>


<script>
    // Function to update the date and time element
    function updateDateTime() {
        const dateTimeElement = document.getElementById('date-time');
        const currentDate = new Date();
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
        };
        const formattedDate = currentDate.toLocaleDateString('en-US', options);

        dateTimeElement.textContent = formattedDate;
    }

    // Call the function to update the date and time immediately
    updateDateTime();

    // Set an interval to update the date and time every second (1000 milliseconds)
    setInterval(updateDateTime, 1000);
</script>