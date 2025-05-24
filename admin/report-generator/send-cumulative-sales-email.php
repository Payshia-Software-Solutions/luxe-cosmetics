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

$fromQueryDate = isset($_GET['fromQueryDate']) && $_GET['fromQueryDate'] !== ''
    ? $_GET['fromQueryDate']
    : date('Y-m-01'); // First date of the current month

$toQueryDate = isset($_GET['toQueryDate']) && $_GET['toQueryDate'] !== ''
    ? $_GET['toQueryDate']
    : date('Y-m-t'); // Last date of the current month  // Set to today's date if not provided
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
        table { border-collapse: collapse; width: 100%; max-width: 100%; table-layout: auto; word-wrap: break-word; }
        th, td { border: 1px solid black; padding: 4px; text-align: left; font-size: 12px; }
        th { background-color: #f2f2f2; }
        .text-end { text-align: right; }
        .text-bold-extra { font-weight: bold; }
        h3 {margin-bottom:0px}
        p {margin-bottom:0px}
        .report-header{font-size:18px}
        </style>
    </head>
    <body>
        <table style='width: 100%; border: none; border-collapse: collapse; margin-bottom: 20px;'>
                <tr>
                    <td style='width: 50%; vertical-align: top; padding-right: 10px; border: none;'>
                        <h3 class='report-header' style='margin: 0;'>{$CompanyInfo['company_name']}</h3>
                        <p style='margin: 0;'>{$CompanyInfo['company_address']}, {$CompanyInfo['company_address2']}</p>
                        <p style='margin: 0;'>{$CompanyInfo['company_city']}, {$CompanyInfo['company_postalcode']}</p>
                        <p style='margin: 0;'>Tel: {$CompanyInfo['company_telephone']}</p>
                        <p style='margin: 0;'>Email: {$CompanyInfo['company_email']}</p>
                    </td>
                    <td style='width: 50%; vertical-align: top; text-align: right; padding-left: 10px; border: none;'>
                        <h3 class='report-header' style='margin: 0;'>Item Wise Sale</h3>
                        <p style='margin: 0;'>From: $formattedFromQueryDate</p>
                        <p style='margin: 0;'>To: $formattedToQueryDate</p>
                        <p style='margin: 0;'>Location: $location_name</p>
                        <p style='margin: 0;'>Generated on: $reportDate</p>
                    </td>
                </tr>
            </table>

        <div class='invoice'>
        
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
            <td class='text-end text-bold-extra'>" . formatAccountBalance(-$totalDiscount) . "</td>
            <td class='text-end text-bold-extra'>" . formatAccountBalance($totalSale) . "</td>
        </tr>
        <tr>
            <td colspan='5' class='text-end text-bold-extra'>Bill Discount</td>
            <td class='text-end text-bold-extra'>" . formatAccountBalance(-$discountAmount) . "</td>
        </tr>
        <tr>
            <td colspan='5' class='text-end text-bold-extra'>Charge</td>
            <td class='text-end text-bold-extra'>" . formatAccountBalance($serviceCharge) . "</td>
        </tr>
        <tr>
            <td colspan='5' class='text-end text-bold-extra'>Total Return</td>
            <td class='text-end text-bold-extra'>" . formatAccountBalance(-$totalReturn) . "</td>
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

    // Generate PDF using DOMPDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait'); // Set paper size
    $dompdf->render();


    // Output the PDF
    return $dompdf->output();
}


function generateSaleSummaryReport($fromQueryDate, $toQueryDate, $location_id)
{
    global $link, $Locations, $CompanyInfo;
    $returnSettlement = 0;
    $invoiceSales = getInvoicesByDateRangeAll($link, $fromQueryDate, $toQueryDate, $location_id);

    $LocationID = $location_id;
    $fromDate = new DateTime($fromQueryDate);
    $formattedFromQueryDate = $fromDate->format('d/m/Y');

    $toDate = new DateTime($toQueryDate);
    $formattedToQueryDate = $toDate->format('d/m/Y');

    $generateDate = new DateTime();
    $reportDate = $generateDate->format('d/m/Y H:i:s');

    $location_name = $Locations[$LocationID]['location_name'];
    $reportTitle = "Sale Summary Report";

    $subTotal = $discountAmount = $serviceCharge = $grandTotal = $returnTotal = 0;

    // Build HTML content for PDF
    $html = '<html><head><style>
        body { font-family: Arial, sans-serif; }
        table { border-collapse: collapse; width: 100%; max-width: 100%; table-layout: fixed; word-wrap: break-word; }
        th, td { border: 1px solid black; padding: 4px; text-align: left; font-size: 12px; }
        th { background-color: #f2f2f2; }
        .text-end { text-align: right; }
        .text-bold-extra { font-weight: bold; }
        h3 {margin-bottom:0px}
        p {margin-bottom:0px}
        .report-header{font-size:18px}
    </style></head><body>';

    $html .= "
            <table style='width: 100%; border: none; border-collapse: collapse; margin-bottom: 20px;'>
                <tr>
                    <td style='width: 50%; vertical-align: top; padding-right: 10px; border: none;'>
                        <h3 class='report-header' style='margin: 0;'>{$CompanyInfo['company_name']}</h3>
                        <p style='margin: 0;'>{$CompanyInfo['company_address']}, {$CompanyInfo['company_address2']}</p>
                        <p style='margin: 0;'>{$CompanyInfo['company_city']}, {$CompanyInfo['company_postalcode']}</p>
                        <p style='margin: 0;'>Tel: {$CompanyInfo['company_telephone']}</p>
                        <p style='margin: 0;'>Email: {$CompanyInfo['company_email']}</p>
                    </td>
                    <td style='width: 50%; vertical-align: top; text-align: right; padding-left: 10px; border: none;'>
                        <h3 class='report-header' style='margin: 0;'>{$reportTitle}</h3>
                        <p style='margin: 0;'>From: $formattedFromQueryDate</p>
                        <p style='margin: 0;'>To: $formattedToQueryDate</p>
                        <p style='margin: 0;'>Location: $location_name</p>
                        <p style='margin: 0;'>Generated on: $reportDate</p>
                    </td>
                </tr>
            </table>
        ";

    $html .= '<table style="width: 100%; border-collapse: collapse; table-layout: auto;">
        <thead>
            <tr>
                <th>Date</th>
                <th>Invoice #</th>
                <th>Customer</th>
                <th>Type</th>
                <th>Net Sale</th>
                <th>Discount</th>
                <th>Charge</th>
                <th>Return</th>
                <th>Grand Total</th>
            </tr>
        </thead>
        <tbody>';

    // Loop through the invoices and populate the table rows
    if (!empty($invoiceSales)) {
        foreach ($invoiceSales as $selectedArray) {
            $returnSettlement = GetInvoiceSettlement($selectedArray['invoice_number']);
            $subTotal += $selectedArray['inv_amount'];
            $discountAmount += $selectedArray['discount_amount'];
            $serviceCharge += $selectedArray['service_charge'];
            $returnTotal += $returnSettlement;
            $grandTotal += ($selectedArray['grand_total'] - $returnSettlement);

            $invoice_date = date("d/m/Y", strtotime($selectedArray['current_time']));

            $html .= "<tr>
                <td>$invoice_date</td>
                <td>{$selectedArray['invoice_number']}</td>
                <td style='max-width:10%'>{$selectedArray['customer_code']}</td>
                <td>{$selectedArray['payment_status']}</td>
                <td class='text-end'>" . number_format($selectedArray['inv_amount'], 2) . "</td>
                <td class='text-end'>" . number_format($selectedArray['discount_amount'], 2) . "</td>
                <td class='text-end'>" . number_format($selectedArray['service_charge'], 2) . "</td>
                <td class='text-end'>" . number_format($returnSettlement ?? 0, 2) . "</td>
                <td class='text-end'>" . number_format($selectedArray['grand_total'] - $returnSettlement, 2) . "</td>
            </tr>";
        }
    }

    // Add totals row
    $html .= "<tr>
        <td colspan='4' class='text-end text-bold-extra'>Totals</td>
        <td class='text-end text-bold-extra'>" . number_format($subTotal, 2) . "</td>
        <td class='text-end text-bold-extra'>" . number_format($discountAmount, 2) . "</td>
        <td class='text-end text-bold-extra'>" . number_format($serviceCharge, 2) . "</td>
        <td class='text-end text-bold-extra'>" . number_format($returnTotal, 2) . "</td>
        <td class='text-end text-bold-extra'>" . number_format($grandTotal, 2) . "</td>
    </tr>";

    $html .= '</tbody></table></body></html>';

    // Generate PDF using DOMPDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();


    // Output the PDF
    return $dompdf->output();
}

function generateDateWiseSaleSummaryReport($fromQueryDate, $toQueryDate, $location_id)
{
    global $link, $Locations, $CompanyInfo;


    $LocationID = $location_id;
    $fromDate = new DateTime($fromQueryDate);
    $formattedFromQueryDate = $fromDate->format('d/m/Y');

    $toDate = new DateTime($toQueryDate);
    $formattedToQueryDate = $toDate->format('d/m/Y');

    $generateDate = new DateTime();
    $reportDate = $generateDate->format('d/m/Y H:i:s');

    $location_name = $Locations[$LocationID]['location_name'];
    $invoiceSales = GetDateWiseSaleReport($fromQueryDate, $toQueryDate, $location_id);

    $cumulativeInvoiceAmount = $cumulativeDiscountAmount = $cumulativeServiceCharge = $cumulativeSaleAmount  = $cumulativeInvoiceCount = $cumulativeReturn = $cumulativeSubTotalAmount = $cumulativeUnSettledReturn = $cumulativeCashSale = $cumulativeCreditSale = $cumulativeOtherSale = $cumulativeCreditCollection = $cumulativeSettledReturn  = $cumulativeCardSale = 0;

    // Build HTML content for PDF
    $html = '<html><head><style>
        body { font-family: Arial, sans-serif; }
        table { border-collapse: collapse; width: 100%; max-width: 100%; table-layout: fixed; word-wrap: break-word; }
        th, td { border: 1px solid black; padding: 4px; text-align: left; font-size: 12px; }
        th { background-color: #f2f2f2; }
        .text-end { text-align: right; }
        .text-bold-extra { font-weight: bold; }
        h3 {margin-bottom:0px}
        p {margin-bottom:0px}
        .report-header{font-size:18px}
    </style></head><body>';

    $html .= "
            <table style='width: 100%; border: none; border-collapse: collapse; margin-bottom: 20px;'>
                <tr>
                    <td style='width: 50%; vertical-align: top; padding-right: 10px; border: none;'>
                        <h3 class='report-header' style='margin: 0;'>{$CompanyInfo['company_name']}</h3>
                        <p style='margin: 0;'>{$CompanyInfo['company_address']}, {$CompanyInfo['company_address2']}</p>
                        <p style='margin: 0;'>{$CompanyInfo['company_city']}, {$CompanyInfo['company_postalcode']}</p>
                        <p style='margin: 0;'>Tel: {$CompanyInfo['company_telephone']}</p>
                        <p style='margin: 0;'>Email: {$CompanyInfo['company_email']}</p>
                    </td>
                    <td style='width: 50%; vertical-align: top; text-align: right; padding-left: 10px; border: none;'>
                        <h3 class='report-header' style='margin: 0;'>Date Wise Sale Report</h3>
                        <p style='margin: 0;'>From: $formattedFromQueryDate</p>
                        <p style='margin: 0;'>To: $formattedToQueryDate</p>
                        <p style='margin: 0;'>Location: $location_name</p>
                        <p style='margin: 0;'>Generated on: $reportDate</p>
                    </td>
                </tr>
            </table>
        ";

    $html .= '<table style="width: 100%; border-collapse: collapse; table-layout: auto;">
        <thead>
            <tr>
            <th scope="col">Date</th>
            <th scope="col">PCs</th>
            <th scope="col">Invoice Value</th>
            <th scope="col">Discount</th>
            <th scope="col">Charge</th>
            <th scope="col">Settled Return</th>
            <th scope="col">Total Sale</th>
            <th scope="col">Cash</th>
            <th scope="col">Credit</th>
            <th scope="col">Card Payment</th>
            <th scope="col">Other</th>
            <th scope="col">Credit Collection</th>
            <th scope="col">Unsettled Return</th>
            </tr>
        </thead>
        <tbody>';
    // Loop through the invoices and populate the table rows
    if (!empty($invoiceSales)) {
        foreach ($invoiceSales as $selectedArray) {
            $invoice_date = $selectedArray['date'];
            $totalSubTotalAmount = $selectedArray['total_invoice_amount'];
            $totalInvoiceCount = $selectedArray['total_invoices'];
            $totalSaleAmount = $selectedArray['total_sales_amount'];
            $totalDiscountAmount = $selectedArray['total_discount_amount'];
            $totalServiceCharge = $selectedArray['total_service_charge'];

            $creditCardReceipts = $cashReceipts = $creditSales = $invoiceSales = $refundAmount = $cashInHand = $otherTypesSale = 0;
            $receipts =  getReceiptsByDate($link, $invoice_date, $location_id);
            $CreditCollection =  getReceiptsCollection($link, $invoice_date, $location_id);
            $returnAmounts = GetUnsettledReturnValuesTotal($link, $invoice_date, $invoice_date, $location_id);
            $receiptsInfo = getReceiptsByDateRangeByType($link, $invoice_date, $invoice_date, $location_id);


            $inRangeCashReceipts = $AllCashReceipts = $inRangeCashCollection = 0;
            $inRangeCardReceipts = $AllCardReceipts = $inRangeCardCollection = 0;
            if (isset($receiptsInfo['Cash'])) {
                $inRangeCashReceipts = $receiptsInfo['Cash']['inRangeReceipts'];
                $AllCashReceipts = $receiptsInfo['Cash']['AllReceipts'];
                $inRangeCashCollection = $AllCashReceipts - $inRangeCashReceipts;
            }

            if (isset($receiptsInfo['Visa/Master'])) {
                $inRangeCardReceipts = $receiptsInfo['Visa/Master']['inRangeReceipts'];
                $AllCardReceipts = $receiptsInfo['Visa/Master']['AllReceipts'];
                $inRangeCardCollection = $AllCashReceipts - $inRangeCashReceipts;
            }

            $inRangeOtherReceipts = $AllOtherReceipts = 0;

            if (!empty($receiptsInfo)) {
                foreach ($receiptsInfo as $receiptArray) {
                    $paymentType = $receiptArray['PaymentType'];
                    if ($paymentType === "Cash" || $paymentType === "Visa/Master") {
                        continue;
                    }

                    $inRangeOtherReceipts += $receiptArray['inRangeReceipts'];
                    $AllOtherReceipts += $receiptArray['AllReceipts'];
                }
            }

            $inRangeOtherCollection = $AllOtherReceipts - $inRangeOtherReceipts;
            $totalReceipts = $AllCashReceipts + $creditCardReceipts + $otherTypesSale;
            $CreditCollection = $inRangeCardCollection + $inRangeCashCollection + $inRangeOtherCollection;
            $dayReceipts = $inRangeCardReceipts + $inRangeOtherReceipts + $inRangeCashReceipts;

            $returnAmount = $returnAmounts['return_amount'];
            $settledReturnAmount = $returnAmounts['total_settled_amount'];
            $unsettled_amount = $returnAmounts['unsettled_amount'];

            $totalSaleAmount = $selectedArray['total_sales_amount'] - $settledReturnAmount;
            $creditSales = $selectedArray['total_sales_amount'] - ($dayReceipts + $settledReturnAmount);
            $cashInHand = $AllCashReceipts - $refundAmount;


            $cumulativeInvoiceCount += $totalInvoiceCount;
            $cumulativeSubTotalAmount += $totalSubTotalAmount;
            $cumulativeDiscountAmount += $totalDiscountAmount;
            $cumulativeServiceCharge += $totalServiceCharge;
            $cumulativeSaleAmount += $totalSaleAmount;
            $cumulativeSettledReturn += $settledReturnAmount;
            $cumulativeUnSettledReturn += $unsettled_amount;

            $cumulativeCashSale += $inRangeCashReceipts;
            $cumulativeCreditSale += $creditSales;
            $cumulativeCardSale += $inRangeCardReceipts;
            $cumulativeOtherSale += $otherTypesSale;
            $cumulativeCreditCollection += $CreditCollection;

            if ($totalInvoiceCount != 0 || $totalSubTotalAmount != 0 || $totalDiscountAmount != 0 || $totalServiceCharge != 0 || $settledReturnAmount != 0 || $totalSaleAmount != 0 || $inRangeCashReceipts != 0 || $creditSales != 0 || $inRangeCardReceipts != 0 || $inRangeOtherReceipts != 0 || $CreditCollection != 0 || $unsettled_amount != 0) {

                $html .= "<tr>
                <td style='white-space: nowrap;'>" . $invoice_date . "</td>
                <td class='text-end'>" . formatAccountBalance($totalInvoiceCount) . "</td>
                <td class='text-end'>" . formatAccountBalance($totalSubTotalAmount) . "</td>
                <td class='text-end'>" . formatAccountBalance(-$totalDiscountAmount) . "</td>
                <td class='text-end'>" . formatAccountBalance($totalServiceCharge) . "</td>
                <td class='text-end'>" . formatAccountBalance(-$settledReturnAmount) . "</td>
                <td class='text-end'>" . formatAccountBalance($totalSaleAmount) . "</td>
                <td class='text-end'>" . formatAccountBalance($inRangeCashReceipts) . "</td>
                <td class='text-end'>" . formatAccountBalance($creditSales) . "</td>
                <td class='text-end'>" . formatAccountBalance($inRangeCardReceipts) . "</td>
                <td class='text-end'>" . formatAccountBalance($inRangeOtherReceipts) . "</td>
                <td class='text-end'>" . formatAccountBalance($CreditCollection) . "</td>
                <td class='text-end'>" . formatAccountBalance($unsettled_amount) . "</td>
            </tr>";
            }
        }
    }


    // Add totals row
    $html .= " <tr>
    <td class='text-end border-bottom text-bold-extra'></td>
    <td class='text-end border-bottom text-bold-extra'>" . formatAccountBalance($cumulativeInvoiceCount) . "</td>
    <td class='text-end border-bottom text-bold-extra'>" . formatAccountBalance($cumulativeSubTotalAmount) . "</td>
    <td class='text-end border-bottom text-bold-extra'>" . formatAccountBalance(-$cumulativeDiscountAmount) . "</td>
    <td class='text-end border-bottom text-bold-extra'>" . formatAccountBalance($cumulativeServiceCharge) . "</td>
    <td class='text-end border-bottom text-bold-extra'>" . formatAccountBalance(-$cumulativeSettledReturn) . "</td>
    <td class='text-end border-bottom text-bold-extra'>" . formatAccountBalance($cumulativeSaleAmount) . "</td>
    <td class='text-end border-bottom text-bold-extra'>" . formatAccountBalance($cumulativeCashSale) . "</td>
    <td class='text-end border-bottom text-bold-extra'>" . formatAccountBalance($cumulativeCreditSale) . "</td>
    <td class='text-end border-bottom text-bold-extra'>" . formatAccountBalance($cumulativeCardSale) . "</td>
    <td class='text-end border-bottom text-bold-extra'>" . formatAccountBalance($cumulativeOtherSale) . "</td>
    <td class='text-end border-bottom text-bold-extra'>" . formatAccountBalance($cumulativeCreditCollection) . "</td>
    <td class='text-end border-bottom text-bold-extra'>" . formatAccountBalance($cumulativeUnSettledReturn) . "</td>
</tr>";
    $html .= '</tbody></table></body></html>';

    // Generate PDF using DOMPDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape'); // Set paper size to landscape
    $dompdf->render();

    // Output the PDF
    return $dompdf->output();
}


$mail = new PHPMailer(true);

try {
    // Generate PDFs
    $saleSummaryPdfContent = generateSaleSummaryReport($fromQueryDate, $toQueryDate, $location_id); // Generate Sale Summary Report PDF
    $itemWiseSalePdfContent = generateItemWiseSaleReport($fromQueryDate, $toQueryDate, $location_id); // Generate Itemwise Sale Report PDF

    $DateWiseSalePdfContent = generateDateWiseSaleSummaryReport($fromQueryDate, $toQueryDate, $location_id); // Generate Itemwise Sale Report PDF

    // Server settings
    $mail->isSMTP();
    $mail->Host = 'mail.teajarceylon.com';  // SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'no-reply@teajarceylon.com';  // SMTP username
    $mail->Password = 'g85zvB]2;Hnf';  // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  // Use implicit TLS encryption
    $mail->Port = 465;  // TCP port for SMTP

    // Recipients
    $mail->setFrom('no-reply@teajarceylon.com', 'Tea Jar | Finest Ceylon Tea');
    $mail->addAddress('deelakaupasena.sg@gmail.com');

    $mail->addCC('dupasena@kdugroup.com');
    $mail->addCC('marketing@teajarceylon.com');
    $mail->addCC('international@kduexports.com');
    $mail->addBCC('thilinaruwan112@gmail.com');
    // Attach the PDF file
    // Add Sale Summary Report attachment// Add Sale Summary Report attachment with dates
    $mail->addStringAttachment(
        $saleSummaryPdfContent,
        'Sale-Summary-Report-' . $fromQueryDate . '-to-' . $toQueryDate . '.pdf',
        'base64',
        'application/pdf'
    );

    // Add ItemWise Sale Report attachment with dates
    $mail->addStringAttachment(
        $itemWiseSalePdfContent,
        'ItemWise-Sale-Report-' . $fromQueryDate . '-to-' . $toQueryDate . '.pdf',
        'base64',
        'application/pdf'
    );

    // Add ItemWise Sale Report attachment with dates
    $mail->addStringAttachment(
        $DateWiseSalePdfContent,
        'DateWise-Sale-Report-' . $fromQueryDate . '-to-' . $toQueryDate . '.pdf',
        'base64',
        'application/pdf'
    );


    // Generate email content
    // $emailContent = $this->generateEmailHTML($orderData);

    // Content
    $mail->isHTML(true); // Email format is HTML
    $mail->Subject = ($fromQueryDate === $toQueryDate)
        ? 'Cumulative Sales Report in ' . $fromQueryDate . " | Tea Jar Webstore"
        : 'Cumulative Sales Report in ' . $fromQueryDate . ' - ' . $toQueryDate . " | Tea Jar Webstore";
    $mail->Body = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333333;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 5px;
            overflow: hidden;
        }
        .header {
            background-color: #007bff;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .content p {
            margin: 10px 0;
            font-size: 16px;
        }
        .content ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .content ul li {
            font-size: 15px;
        }
        .footer {
            background-color: #f4f4f4;
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #666666;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class='email-container'>
        <div class='header'>
            <h1>Tea Jar | Finest Ceylon Tea</h1>
            <p>Cumulative Sales Report</p>
        </div>
        <div class='content'>
            <p>Dear Team,</p>
            <p>This is an <strong>automated email</strong> to provide the sales reports for the period from <strong>$fromQueryDate</strong> to <strong>$toQueryDate</strong>.</p>
            <p>The reports include:</p>
            <ul>
                <li><strong>Sale Summary Report:</strong> A high-level overview of sales during the specified period.</li>
                <li><strong>Item-wise Sale Report:</strong> Detailed breakdown of sales per item.</li>
                <li><strong>Date-wise Sale Report:</strong> Detailed breakdown of sales daily.</li>
            </ul>
            <p>Please find the reports attached for your reference.</p>
            <p>Best regards,</p>
            <p><strong>Admin</strong></p>
        </div>
        <div class='footer'>
            <p>&copy; " . date('Y') . " Tea Jar. All rights reserved.</p>
            <p>Please do not reply to this email as it is automatically generated.</p>
            <p>For assistance, contact us at <a href='mailto:marketing@teajarceylon.com'>marketing@teajarceylon.com</a>.</p>
        </div>
    </div>
</body>
</html>";



    // Send the email
    $mail->send();
    echo 'Email Sent Successfully';
} catch (Exception $e) {
    // Log the error
    error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
    $mailError = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    echo $mailError;
}
