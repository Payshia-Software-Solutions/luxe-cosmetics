<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require './vendor/autoload.php';
require_once './models/ContactMessage.php';

class ContactMessagesController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new ContactMessage($pdo);
    }

    // Handle creating a new message
    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (
            isset($data['full_name'], $data['email'], $data['message'], $data['policy']) &&
            filter_var($data['email'], FILTER_VALIDATE_EMAIL) &&
            $data['policy'] === true
        ) {
            $this->model->createMessage($data);

            // Send email notification
            $emailResult = $this->sendContactEmail($data);

            http_response_code(201);
            echo json_encode(['message' => 'Contact message submitted successfully', 'emailStatus' => $emailResult]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Get all messages
    public function getAllRecords()
    {
        $messages = $this->model->getAllMessages();
        echo json_encode($messages);
    }

    // Get a message by ID
    public function getRecordById($id)
    {
        $message = $this->model->getMessageById($id);
        if ($message) {
            echo json_encode($message);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Message not found']);
        }
    }

    // Delete a message by ID
    public function deleteRecord($id)
    {
        $deleted = $this->model->deleteMessage($id);
        if ($deleted) {
            echo json_encode(['message' => 'Contact message deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Message not found']);
        }
    }

    function generateContactEmailHTML($contactData)
    {
        // Define the template
        $template = file_get_contents('./templates/contact_template.html'); // Adjust the path to the HTML template file

        // Replace placeholders with actual data
        $replacements = [
            '[FULL_NAME]' => htmlspecialchars($contactData['full_name']),
            '[EMAIL]' => htmlspecialchars($contactData['email']),
            '[PHONE]' => !empty($contactData['phone']) ? htmlspecialchars($contactData['phone']) : 'N/A',
            '[SUBJECT]' => htmlspecialchars($contactData['subject']),
            '[MESSAGE]' => nl2br(htmlspecialchars($contactData['message'])),
            '[NEWSLETTER]' => $contactData['newsletter'] ? 'Yes' : 'No',
            '[POLICY_AGREEMENT]' => $contactData['policy'] ? 'Agreed' : 'Not Agreed',
            '[ADMIN_EMAIL]' => 'marketing@teajarceylon.com', // Set admin email or replace dynamically if needed
            '[COMPANY_ADDRESS]' => 'KDU Exports PVT LTD, 427 A, Galle Road, Colombo 03, Sri Lanka', // Update with actual company address
            '[COMPANY_CONTACT]' => '(+94) 70 55 08 800', // Update with actual contact number
            '[INSTAGRAM_URL]' => 'https://instagram.com/teajar', // Update with actual link
            '[FACEBOOK_URL]' => 'https://facebook.com/teajar', // Update with actual link
            '[UNSUBSCRIBE_URL]' => 'https://teajarceylon.com/unsubscribe' // Update with actual unsubscribe link
        ];

        // Replace placeholders in the template
        foreach ($replacements as $placeholder => $value) {
            $template = str_replace($placeholder, $value, $template);
        }

        return $template;
    }

    public function sendContactEmail($contactData)
    {
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
            $mail->addAddress('marketing@teajarceylon.com'); // Email recipient for contact form submissions

            // Optional CCs (you can add or remove as needed)
            $mail->addCC('dupasena@kdugroup.com');
            $mail->addCC($contactData['email']);
            $mail->addCC('international@teajarceylon.com');

            // Generate email content
            $emailContent = $this->generateContactEmailHTML($contactData);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $contactData['subject'] . ' | New Contact Form Submission - Tea Jar';  // Subject
            $mail->Body = $emailContent;  // Email body content

            // Send the email
            $mail->send();
            return ['status' => 'success', 'message' => 'Contact email sent successfully'];
        } catch (Exception $e) {
            // Log the error
            error_log("Contact Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
            $mailError = "Contact Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return ['status' => 'error', 'message' => $mailError];
        }
    }

    public function sendNewsLetter()
    {
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
            $mail->addAddress('thilinaruwan112@gmail.com'); // Email recipient for contact form submissions

            // $mail->addCC('su28lakmal@gmail.com');
            // $mail->addCC('deelakaupasena.sg@gmail.com');

            // $mail->addCC('saviskaniyomal@gmail.com');
            // Optional CCs (you can add or remove as needed)

            // Define the template
            // $template = file_get_contents('./templates/contact_template.html'); // Adjust the path to the HTML template file
            $template = file_get_contents('./templates/newsletters/teajar_newsletter2.html'); // Adjust the path to the HTML template file

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'News Letter - Tea Jar';  // Subject
            $mail->Body = $template;  // Email body content

            // Send the email
            $mail->send();
            return ['status' => 'success', 'message' => 'Contact email sent successfully'];
        } catch (Exception $e) {
            // Log the error
            error_log("Contact Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
            $mailError = "Contact Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return ['status' => 'error', 'message' => $mailError];
        }
    }
}
