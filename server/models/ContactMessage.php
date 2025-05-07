<?php
class ContactMessage
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Create a new contact message
    public function createMessage($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO `contact_messages` (`full_name`, `email`, `phone`, `subject`, `message`, `newsletter`, `policy`)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['full_name'],
            $data['email'],
            $data['phone'],
            $data['subject'],
            $data['message'],
            $data['newsletter'],
            $data['policy']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Fetch all contact messages
    public function getAllMessages()
    {
        $stmt = $this->pdo->query("SELECT * FROM `contact_messages` ORDER BY `created_at` DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a contact message by ID
    public function getMessageById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `contact_messages` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Delete a contact message by ID
    public function deleteMessage($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `contact_messages` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
