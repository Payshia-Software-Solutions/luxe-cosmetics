<?php

class ContactUs {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Create a new contact message
    public function createMessage($data) {
        $stmt = $this->pdo->prepare("INSERT INTO `contact_us` (`full_name`, `email`, `phone`, `subject`, `message`) 
                                     VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['full_name'],
            $data['email'],
            $data['phone'],
            $data['subject'],
            $data['message']
        ]);

        return $this->pdo->lastInsertId(); // Return the inserted ID
    }

    // Optional: Fetch all messages (for admin/dashboard usage)
    public function getAllMessages() {
        $stmt = $this->pdo->query("SELECT * FROM `contact_us` ORDER BY `created_at` DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Optional: Fetch a specific message
    public function getMessageById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM `contact_us` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Optional: Delete message
    public function deleteMessage($id) {
        $stmt = $this->pdo->prepare("DELETE FROM `contact_us` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>
