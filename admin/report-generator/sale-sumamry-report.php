<?php
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include FPDF
require '../vendor/setasign/fpdf/fpdf.php';

// Generate PDF
class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Sales Summary Report', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function generateReport($data)
    {
        $this->SetFont('Arial', '', 12);
        foreach ($data as $row) {
            $this->Cell(50, 10, $row['product'], 1);
            $this->Cell(30, 10, $row['quantity'], 1, 0, 'R');
            $this->Cell(30, 10, '$' . $row['price'], 1, 0, 'R');
            $this->Cell(30, 10, '$' . $row['total'], 1, 1, 'R');
        }
    }
}

// Example sales data
$salesData = [
    ['product' => 'Product A', 'quantity' => 5, 'price' => 10, 'total' => 50],
    ['product' => 'Product B', 'quantity' => 3, 'price' => 20, 'total' => 60],
    ['product' => 'Product C', 'quantity' => 2, 'price' => 15, 'total' => 30],
];

// Generate PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->generateReport($salesData);
$pdfOutput = 'sales_summary.pdf';
$pdf->Output('I', $pdfOutput); // Save to file

// // Send Email with PHPMailer
// $mail = new PHPMailer(true);

// try {
//     $mail->isSMTP();
//     $mail->Host = 'smtp.example.com'; // Your SMTP server
//     $mail->SMTPAuth = true;
//     $mail->Username = 'your-email@example.com'; // Your email
//     $mail->Password = 'your-email-password'; // Your email password
//     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
//     $mail->Port = 587;

//     // Recipients
//     $mail->setFrom('your-email@example.com', 'Sales Team');
//     $mail->addAddress('recipient@example.com', 'Recipient Name');

//     // Attachments
//     $mail->addAttachment($pdfOutput);

//     // Content
//     $mail->isHTML(true);
//     $mail->Subject = 'Sales Summary Report';
//     $mail->Body = 'Please find the attached sales summary report.';

//     $mail->send();
//     echo 'Email sent successfully!';
// } catch (Exception $e) {
//     echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
// }

// // Cleanup
// if (file_exists($pdfOutput)) {
//     unlink($pdfOutput); // Delete the temporary PDF file
// }
