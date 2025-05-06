<?php

require_once './models/User/UserAccount.php';

class UserAccountController {

    private $model;

    public function __construct($pdo) {
        $this->model = new UserAccount($pdo);
    }

    // Get all user accounts
    public function getAllUsers() {
        try {
            $users = $this->model->getAllUsers();
            echo json_encode([
                'status' => 200,
                'data' => $users,
                'message' => 'Users fetched successfully'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to fetch users: ' . $e->getMessage()]);
        }
    }

    // Get a user account by ID
    public function getUserById($id) {
        try {
            $user = $this->model->getUserById($id);
            if ($user) {
                echo json_encode([
                    'status' => 200,
                    'data' => $user,
                    'message' => 'User fetched successfully'
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'User not found']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to fetch user: ' . $e->getMessage()]);
        }
    }

    // Create a new user account
    public function createUser() {
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            // Validate required fields
            if ($this->isValidUserData($data)) {
                $data['created_at'] = date('Y-m-d H:i:s'); // Set created_at timestamp
                $this->model->createUser($data);
                http_response_code(201);
                echo json_encode([
                    'status' => 201,
                    'message' => 'User created successfully'
                ]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid input data']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create user: ' . $e->getMessage()]);
        }
    }

    // Update an existing user account
    public function updateUser($id) {
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            // Validate required fields
            if ($this->isValidUserData($data)) {
                $this->model->updateUser($id, $data);
                echo json_encode([
                    'status' => 200,
                    'message' => 'User updated successfully'
                ]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid input data']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update user: ' . $e->getMessage()]);
        }
    }

    // Delete a user account by ID
    public function deleteUser($id) {
        try {
            $this->model->deleteUser($id);
            echo json_encode([
                'status' => 200,
                'message' => 'User deleted successfully'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete user: ' . $e->getMessage()]);
        }
    }

    // Validate user input data
    private function isValidUserData($data) {
        return isset($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL) &&
               isset($data['user_name']) && isset($data['pass']) &&
               isset($data['first_name']) && isset($data['last_name']) &&
               isset($data['sex']) && isset($data['PNumber']) &&
               isset($data['update_by']) && isset($data['civil_status']) &&
               isset($data['nic_number']) && strlen($data['PNumber']) === 10;
    }
}

?>
