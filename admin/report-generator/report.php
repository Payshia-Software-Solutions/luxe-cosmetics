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
$invoiceSales = getInvoicesByDateRangeAll($link, $fromQueryDate, $toQueryDate, $location_id);

$LocationID = $location_id;
$fromDate = new DateTime($fromQueryDate);
$formattedFromQueryDate = $fromDate->format('d/m/Y');

$toDate = new DateTime($toQueryDate);
$formattedToQueryDate = $toDate->format('d/m/Y');

$generateDate = new DateTime();
$reportDate = $generateDate->format('d/m/Y H:i:s');

$location_name = $Locations[$LocationID]['location_name'];
$pageTitle = "Sale Summary Report - $fromQueryDate - $toQueryDate";
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
            <th>Sub Total</th>
            <th>Discount</th>
            <th>Charge</th>
            <th>Return</th>
            <th>Grand Total</th>
        </tr>
    </thead>
    <tbody>';

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
            <td class='text-end'>" . number_format($returnSettlement, 2) . "</td>
            <td class='text-end'>" . number_format($selectedArray['grand_total'] - $returnSettlement, 2) . "</td>
        </tr>";
    }
}

$html .= "<tr>
    <td colspan='4' class='text-end text-bold-extra'>Totals</td>
    <td class='text-end text-bold-extra'>" . number_format($subTotal, 2) . "</td>
    <td class='text-end text-bold-extra'>" . number_format($discountAmount, 2) . "</td>
    <td class='text-end text-bold-extra'>" . number_format($serviceCharge, 2) . "</td>
    <td class='text-end text-bold-extra'>" . number_format($returnTotal, 2) . "</td>
    <td class='text-end text-bold-extra'>" . number_format($grandTotal, 2) . "</td>
</tr>";

$html .= '</tbody></table></body></html>';

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait'); // Use landscape orientation for wider content
$dompdf->render();
// $dompdf->stream("Sale_Summary_Report.pdf", ["Attachment" => 1]);


// Display the PDF in the browser
// header('Content-Type: application/pdf');
// Get the generated PDF content
$pdfContent = $dompdf->output();
// echo $pdfContent;

$mail = new PHPMailer(true);

try {
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
    $mail->addAddress('thilinaruwan112@gmail.com'); // Add the customer's email

    // $mail->addCC('dupasena@kdugroup.com');
    // $mail->addCC('marketing@teajarceylon.com');
    // $mail->addCC('international@kduexports.com');
    // Attach the PDF file
    $mail->addStringAttachment($pdfContent, 'Sale_Summary_Report.pdf', 'base64', 'application/pdf');

    // Generate email content
    // $emailContent = $this->generateEmailHTML($orderData);

    // Content
    $mail->isHTML(true); // Email format is HTML
    $mail->Subject = 'Order Confirmation - Tea Jar'; // Email subject
    $mail->Body = "Sale Summary"; // Email body content

    // Send the email
    $mail->send();
    echo 'Email Sent Successfully';
} catch (Exception $e) {
    // Log the error
    error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
    $mailError = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    echo $mailError;
}
