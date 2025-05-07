<?php

class UserAccount {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Get all user accounts
    public function getAllUsers() {
        $stmt = $this->pdo->prepare("SELECT * FROM user_accounts");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a user account by ID
    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM user_accounts WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new user account
    public function createUser($data) {
        $stmt = $this->pdo->prepare("INSERT INTO user_accounts (email, user_name, pass, first_name, last_name, sex, addressl1, addressl2, city, PNumber, WPNumber, created_at, user_status, acc_type, img_path, update_by, civil_status, nic_number) VALUES (:email, :user_name, :pass, :first_name, :last_name, :sex, :addressl1, :addressl2, :city, :PNumber, :WPNumber, :created_at, :user_status, :acc_type, :img_path, :update_by, :civil_status, :nic_number)");
        
        // Prepare data
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':user_name', $data['user_name']);
        $stmt->bindParam(':pass', password_hash($data['pass'], PASSWORD_DEFAULT)); // Hash the password
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':sex', $data['sex']);
        $stmt->bindParam(':addressl1', $data['addressl1']);
        $stmt->bindParam(':addressl2', $data['addressl2']);
        $stmt->bindParam(':city', $data['city']);
        $stmt->bindParam(':PNumber', $data['PNumber']);
        $stmt->bindParam(':WPNumber', $data['WPNumber']);
        $stmt->bindParam(':created_at', $data['created_at']);
        $stmt->bindParam(':user_status', $data['user_status']);
        $stmt->bindParam(':acc_type', $data['acc_type']);
        $stmt->bindParam(':img_path', $data['img_path']);
        $stmt->bindParam(':update_by', $data['update_by']);
        $stmt->bindParam(':civil_status', $data['civil_status']);
        $stmt->bindParam(':nic_number', $data['nic_number']);
        
        return $stmt->execute();
    }

    // Update a user account
    public function updateUser($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE user_accounts SET email = :email, user_name = :user_name, pass = :pass, first_name = :first_name, last_name = :last_name, sex = :sex, addressl1 = :addressl1, addressl2 = :addressl2, city = :city, PNumber = :PNumber, WPNumber = :WPNumber, user_status = :user_status, acc_type = :acc_type, img_path = :img_path, update_by = :update_by, civil_status = :civil_status, nic_number = :nic_number WHERE id = :id");
        
        // Prepare data
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':user_name', $data['user_name']);
        $stmt->bindParam(':pass', password_hash($data['pass'], PASSWORD_DEFAULT)); // Hash the password
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':sex', $data['sex']);
        $stmt->bindParam(':addressl1', $data['addressl1']);
        $stmt->bindParam(':addressl2', $data['addressl2']);
        $stmt->bindParam(':city', $data['city']);
        $stmt->bindParam(':PNumber', $data['PNumber']);
        $stmt->bindParam(':WPNumber', $data['WPNumber']);
        $stmt->bindParam(':user_status', $data['user_status']);
        $stmt->bindParam(':acc_type', $data['acc_type']);
        $stmt->bindParam(':img_path', $data['img_path']);
        $stmt->bindParam(':update_by', $data['update_by']);
        $stmt->bindParam(':civil_status', $data['civil_status']);
        $stmt->bindParam(':nic_number', $data['nic_number']);
        
        return $stmt->execute();
    }

    // Delete a user account
    public function deleteUser($id) {
        $stmt = $this->pdo->prepare("DELETE FROM user_accounts WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
