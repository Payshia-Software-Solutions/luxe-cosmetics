<?php

class WebsiteMode
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fetch the current mode from the database
    public function getMode()
    {
        $stmt = $this->pdo->prepare("SELECT `mode` FROM `website_mode` WHERE `id` = 1");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['mode'] : 'normal'; // Default to 'normal' if no mode is set
    }

    // Update the mode in the database
    public function setMode($newMode)
    {
        $stmt = $this->pdo->prepare("UPDATE `website_mode` SET `mode` = ? WHERE `id` = 1");
        $stmt->execute([$newMode]);
    }
}
