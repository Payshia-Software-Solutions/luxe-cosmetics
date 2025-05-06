<?php
class Department
{
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fetch all departments
    public function getAllDepartments()
    {
        $stmt = $this->pdo->prepare("SELECT `id`, `section_id`, `department_name`, `is_active`, `created_at`, `created_by`, `pos_display` FROM `master_departments` WHERE `is_active` = 1 ORDER BY `order_by` ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single department by ID
    public function getDepartmentById($id)
    {
        $stmt = $this->pdo->prepare("SELECT `id`, `section_id`, `department_name`, `is_active`, `created_at`, `created_by`, `pos_display` FROM `master_departments` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new department
    public function createDepartment($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO `master_departments` (`section_id`, `department_name`, `is_active`, `created_at`, `created_by`, `pos_display`) 
                                     VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['section_id'],
            $data['department_name'],
            $data['is_active'],
            $data['created_at'],
            $data['created_by'],
            $data['pos_display']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Update an existing department
    public function updateDepartment($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE `master_departments` SET 
                                     `section_id` = ?, 
                                     `department_name` = ?, 
                                     `is_active` = ?, 
                                     `created_by` = ?, 
                                     `pos_display` = ? 
                                     WHERE `id` = ?");
        $stmt->execute([
            $data['section_id'],
            $data['department_name'],
            $data['is_active'],
            $data['created_by'],
            $data['pos_display'],
            $id
        ]);
        return $stmt->rowCount();
    }

    // Delete a department by ID
    public function deleteDepartment($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `master_departments` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
