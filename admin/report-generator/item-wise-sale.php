<?php
ini_set('display_errors', 1);  // Enable error display
error_reporting(E_ALL);
require_once '../include/config.php';
require_once '../include/function-update.php';
require_once '../include/finance-functions.php';
require_once '../include/reporting-functions.php';

// Include DOMPDF
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$fromQueryDate = isset($_GET['fromQueryDate']) && $_GET['fromQueryDate'] !== '' ? $_GET['fromQueryDate'] : date('Y-m-d'); // Set to today's date if not provided
$toQueryDate = isset($_GET['toQueryDate']) && $_GET['toQueryDate'] !== '' ? $_GET['toQueryDate'] : date('Y-m-d');   // Set to today's date if not provided
$location_id = isset($_GET['location_id']) && $_GET['location_id'] !== '' ? $_GET['location_id'] : 1; // Set to 1 if not provided

if ($fromQueryDate === null || $toQueryDate === null || $location_id === null) {
    die("Invalid request. Please provide all required parameters with non-empty values.");
}

$Locations = GetLocations($link);
$CompanyInfo = GetCompanyInfo($link);
$Products = GetProducts($link);


function generateItemWiseSaleReport($fromDate, $toDate, $location_id)
{
    global $link, $Locations, $CompanyInfo, $Products;

    // Fetch the necessary data
    $Locations = GetLocations($link);
    $CompanyInfo = GetCompanyInfo($link);
    $Products = GetProducts($link);
    $Units = GetUnit($link);


    // Fetch sale data
    $itemWiseSale = GetItemWiseSale($link, $fromDate, $toDate, $location_id);
    $returnItems = GetReturnByRange($link, $fromDate, $toDate, $location_id);
    $invoiceSales = getInvoicesByDateRangeAll($link, $fromDate, $toDate, $location_id);

    // Initialize totals
    $subTotal = $discountAmount = $serviceCharge = $grandTotal = 0;
    $totalReturn = 0;
    $totalSale = $totalDiscount = $totalCostValue = 0;


    // Prepare date variables
    $location_name = $Locations[$location_id]['location_name'];
    $fromDate = new DateTime($fromDate);
    $formattedFromQueryDate = $fromDate->format('d/m/Y');
    $toDate = new DateTime($toDate);
    $formattedToQueryDate = $toDate->format('d/m/Y');

    // Generate report date
    $generateDate = new DateTime();
    $reportDate = $generateDate->format('d/m/Y H:i:s');

    // Calculate invoice totals
    if (!empty($invoiceSales)) {
        foreach ($invoiceSales as $selectedArray) {
            $subTotal += $selectedArray['inv_amount'];
            $discountAmount += $selectedArray['discount_amount'];
            $serviceCharge += $selectedArray['service_charge'];
            $grandTotal += $selectedArray['grand_total'];
        }
    }

    // HTML content for the report
    $html = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            h1 { text-align: center; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
            th { background-color: #f2f2f2; }
            .text-end { text-align: right; }
            .text-bold { font-weight: bold; }
        </style>
    </head>
    <body>
        <div class='invoice'>
            <div id='container'>
                <div id='left-section'>
                    <h3 class='company-title'>{$CompanyInfo['company_name']}</h3>
                    <p>{$CompanyInfo['company_address']}, {$CompanyInfo['company_address2']}</p>
                    <p>{$CompanyInfo['company_city']}, {$CompanyInfo['company_postalcode']}</p>
                    <p>Tel: {$CompanyInfo['company_telephone']}/ {$CompanyInfo['company_telephone2']}</p>
                    <p>Email: {$CompanyInfo['company_email']}</p>
                    <p>Web: {$CompanyInfo['website']}</p>
                </div>

                <div id='right-section'>
                    <h4 class='report-title-mini'>Item Wise Sale</h4>
                    <table>
                        <tr><th>Location</th><td class='text-end'>{$location_name}</td></tr>
                        <tr><th>From Date</th><td class='text-end'>{$formattedFromQueryDate}</td></tr>
                        <tr><th>To Date</th><td class='text-end'>{$formattedToQueryDate}</td></tr>
                    </table>
                </div>
            </div>

            <p style='font-weight:600;margin-top:10px; margin-bottom:0px'>Report generated on {$reportDate}</p>

            <div id='container' class='section-4'>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Item Price</th>
                            <th>Item Discounts</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>";

    // Loop through item-wise sale data to generate report rows
    $rowCount = 1;
    foreach ($itemWiseSale as $item) {
        $product_name = $Products[$item['product_id']]['product_name'];
        $quantity = $item['total_quantity'];
        $itemPrice = $item['item_price'];
        $itemDiscount = $item['total_discounts'] * $quantity;
        $totalValue = $quantity * $itemPrice;
        $lineTotal = $totalValue - $itemDiscount;

        // Update totals
        $totalSale += $lineTotal;
        $totalDiscount += $itemDiscount;

        $html .= "
        <tr>
            <td class='text-end'>{$rowCount}</td>
            <td>{$product_name}</td>
            <td class='text-end'>{$quantity}</td>
            <td class='text-end'>" . number_format($itemPrice, 3) . "</td>
            <td class='text-end'>" . number_format($itemDiscount, 3) . "</td>
            <td class='text-end text-bold'>" . number_format($lineTotal, 3) . "</td>
        </tr>";

        $rowCount++;
    }

    // Calculate grand total, profit, and other amounts
    $grandTotalSale = $totalSale - $discountAmount + $serviceCharge;
    $netSale = $grandTotalSale - $totalReturn;

    // Add totals to the report
    $html .= "
        <tr>
            <td colspan='4' class='text-end text-bold-extra'>Total</td>
            <td class='text-end text-bold-extra'>" . formatAccountBalance($totalDiscount) . "</td>
            <td class='text-end text-bold-extra'>" . formatAccountBalance($totalSale) . "</td>
        </tr>
        <tr>
            <td colspan='5' class='text-end text-bold-extra'>Total Return</td>
            <td class='text-end text-bold-extra'>" . formatAccountBalance($totalReturn) . "</td>
        </tr>
        <tr>
            <td colspan='5' class='text-end text-bold-extra'>Net Sale</td>
            <td class='text-end text-bold-extra'>" . formatAccountBalance($netSale) . "</td>
        </tr>";

    // Include return items section if any
    if (!empty($returnItems)) {
        $html .= "<tr><td colspan='6' class='text-bold-extra'>Return Items</td></tr>";
        $html .= "<tr><td class='text-bold'>#</td><td class='text-bold' colspan='2'>Product Name</td><td class='text-bold'>Quantity</td><td class='text-bold'>Item Price</td><td class='text-bold'>Total</td></tr>";

        $rowCount = 1;
        foreach ($returnItems as $item) {
            $product_name = $Products[$item['product_id']]['product_name'];
            $quantity = $item['item_qty'];
            $itemPrice = $item['item_rate'];
            $totalValue = $quantity * $itemPrice;

            $html .= "
            <tr>
                <td class='text-end'>{$rowCount}</td>
                <td colspan='2'>{$product_name}</td>
                <td class='text-end'>{$quantity}</td>
                <td class='text-end'>" . number_format($itemPrice, 2) . "</td>
                <td class='text-end text-bold'>" . number_format($totalValue, 2) . "</td>
            </tr>";

            $rowCount++;
        }
    }

    // Close the table and body tags
    $html .= "</tbody></table></div></div></body></html>";

    return $html;
}

$itemWiseSalePdfContent = generateItemWiseSaleReport($fromQueryDate, $toQueryDate, $location_id);
