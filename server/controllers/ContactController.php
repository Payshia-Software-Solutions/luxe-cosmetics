<?php
require_once './models/ContactUs.php';

class ContactUsController {
    private $model;

    public function __construct($pdo) {
        $this->model = new ContactUs($pdo);
    }

    // Create new contact message
    public function createMessage() {
        $data = json_decode(file_get_contents("php://input"), true);

        // Basic validation
        if ($data && isset($data['full_name'], $data['email'], $data['message'])) {
            $contactData = [
                'full_name' => htmlspecialchars(strip_tags($data['full_name'])),
                'email'     => htmlspecialchars(strip_tags($data['email'])),
                'phone'     => htmlspecialchars(strip_tags($data['phone'] ?? '')),
                'subject'   => htmlspecialchars(strip_tags($data['subject'] ?? '')),
                'message'   => htmlspecialchars(strip_tags($data['message']))
            ];

            $this->model->createMessage($contactData);

            http_response_code(201);
            echo json_encode(['message' => 'Your message has been received.']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Required fields are missing.']);
        }
    }

    // Optional: Admin-side retrieval
    public function getAllMessages() {
        $messages = $this->model->getAllMessages();
        echo json_encode($messages);
    }

    public function getMessageById($id) {
        $message = $this->model->getMessageById($id);
        if ($message) {
            echo json_encode($message);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Message not found']);
        }
    }

    public function deleteMessage($id) {
        $deleted = $this->model->deleteMessage($id);
        if ($deleted) {
            echo json_encode(['message' => 'Message deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Message not found']);
        }
    }
}
?>
