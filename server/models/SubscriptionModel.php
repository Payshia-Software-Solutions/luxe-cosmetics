<?php
class SubscriptionModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }


    public function isEmailSubscribed($email)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM subscriptions WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    public function createSubscription($data)
    {
        if ($this->isEmailSubscribed($data['email'])) {
            return false; // Indicate that the email is already subscribed
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO subscriptions (name, email) 
            VALUES (?, ?)
        ");
        return $stmt->execute([$data['name'], $data['email']]);
    }

    // Retrieve all subscribers
    public function getAllSubscribers()
    {
        $stmt = $this->pdo->query("SELECT id, name, email, created_at FROM subscriptions");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
