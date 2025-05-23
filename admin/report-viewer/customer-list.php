<?php
require_once('../include/config.php');
include '../include/function-update.php';
include '../include/finance-functions.php';
include '../include/reporting-functions.php';

$fromQueryDate = isset($_GET['fromQueryDate']) && $_GET['fromQueryDate'] !== '' ? $_GET['fromQueryDate'] : null;
$toQueryDate = isset($_GET['toQueryDate']) && $_GET['toQueryDate'] !== '' ? $_GET['toQueryDate'] : null;
$location_id = isset($_GET['location_id']) && $_GET['location_id'] !== '' ? $_GET['location_id'] : null;

// Check if the required parameters are not set or have empty values
if ($fromQueryDate === null || $toQueryDate === null || $location_id === null) {
    die("Invalid request. Please provide all required parameters with non-empty values.");
}

// Rest of your code goes here...


$Locations = GetLocations($link);
$CompanyInfo = GetCompanyInfo($link);
$Products = GetProducts($link);
$Units = GetUnit($link);

$LocationID = $location_id;
$fromDate = new DateTime($fromQueryDate);
$formattedFromQueryDate = $fromDate->format('d/m/Y');


$toDate = new DateTime($toQueryDate);
$formattedToQueryDate = $toDate->format('d/m/Y');

$generateDAte = new DateTime();
$reportDate = $generateDAte->format('d/m/Y H:i:s');
$LocationName = $Locations[$LocationID]['location_name'];

$pageTitle = "Customer Report - " . $fromQueryDate . " - " . $toQueryDate;
$reportTitle = "Customer Report";

$subTotal = $discountAmount = $serviceCharge = $grandTotal  = $returnTotal = 0;

$invoiceSales = getInvoicesByDateRangeAllwithAddress($link, $fromQueryDate, $toQueryDate, $location_id);
$location_name = $Locations[$location_id]['location_name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>

    <!-- Favicons -->
    <link href="../assets/images/favicon/apple-touch-icon.png" rel="icon">
    <link href="../assets/images/favicon/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/report-viewer.css">

</head>

<body>
    <div class="invoice">
        <div id="container">
            <div id="left-section">
                <h3 class="company-title"><?= $CompanyInfo['company_name'] ?></h3>
                <p><?= $CompanyInfo['company_address'] ?>, <?= $CompanyInfo['company_address2'] ?></p>
                <p><?= $CompanyInfo['company_city'] ?>, <?= $CompanyInfo['company_postalcode'] ?></p>
                <p>Tel: <?= $CompanyInfo['company_telephone'] ?>/ <?= $CompanyInfo['company_telephone2'] ?></p>
                <p>Email: <?= $CompanyInfo['company_email'] ?></p>
                <p>Web: <?= $CompanyInfo['website'] ?></p>
            </div>

            <div id="right-section">
                <h4 class="report-title-mini"><?= strtoupper($reportTitle) ?></h4>
                <table>
                    <tr>
                        <th>From Date</th>
                        <td class="text-end"><?= $formattedFromQueryDate ?></td>
                    </tr>
                    <tr>
                        <th>To Date</th>
                        <td class="text-end"><?= $formattedToQueryDate ?></td>
                    </tr>
                    <tr>
                        <th>Location</th>
                        <td class="text-end"><?= $location_name ?></td>
                    </tr>
                </table>
            </div>

        </div>



        <p style="font-weight:600;margin-top:10px; margin-bottom:0px">Report is generated on <?= $reportDate ?></p>
        <div id="container" class="section-4">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Customer</th>
                        <th scope="col">Phone Number</th>
                        <th scope="col">Name</th>
                        <th scope="col">Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($invoiceSales)) {
                        foreach ($invoiceSales as $selectedArray) {
                            $referenceText = "";

                            $returnSettlement =  GetInvoiceSettlement($selectedArray['invoice_number']);

                            $invoice_date = date("Y-m-d H:i", strtotime($selectedArray['current_time']));
                            $invoice_date = date("Y-m-d", strtotime($selectedArray['current_time']));
                            $subTotal += $selectedArray['inv_amount'];
                            $ref_hold = $selectedArray['ref_hold'];

                            $CustomerID = $selectedArray['customer_code'];
                            // $Customer = GetCustomersByID($link, $CustomerID);


                            if ($ref_hold == '0') {
                                // $referenceText = "Take Away";
                                $referenceText = "Direct";
                            } else if ($ref_hold == '-1') {
                                // $referenceText = "Retail";
                                $referenceText = "Direct";
                            } else if ($ref_hold == '-2') {
                                // $referenceText = "Delivery";
                                $referenceText = "Direct";
                            } else if ($ref_hold == "") {
                                // $referenceText = "None";
                                $referenceText = "Direct";
                            } else {
                                $referenceText = $ref_hold;
                            }
                    ?>
                    <tr>
                        <td class="border-bottom"><?= $selectedArray['customer_code'] ?></td>
                        <td class="border-bottom"><?= $selectedArray['phone'] ?></td>
                        <td class="border-bottom"><?= $selectedArray['first_name'] ?> <?= $selectedArray['last_name'] ?>
                        </td>
                        <td class="border-bottom"><?= $selectedArray['address_line1'] ?>,
                            <?= $selectedArray['address_line2'] ?>,
                            <?= $selectedArray['city'] ?>, <?= $selectedArray['postal_code'] ?></td>
                    </tr>

                    <?php
                        }
                    }
                    ?>

                </tbody>
            </table>
        </div>

        <script>
        window.print();

        // // Close the window after printing
        // window.onafterprint = function() {
        //     window.close();
        // };
        </script>
    </div>

</body>

</html>