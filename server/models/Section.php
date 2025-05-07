<?php
class Sections
{
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fetch all sections
    public function getAllSections()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `master_sections` WHERE `is_active` = 1 ORDER BY `pos_display` ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single section by ID
    public function getSectionById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `master_sections` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new section
    public function createSection($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO `master_sections` (`section_name`, `is_active`, `created_at`, `created_by`, `pos_display`) 
                                     VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['section_name'],
            $data['is_active'],
            $data['created_at'],
            $data['created_by'],
            $data['pos_display']
        ]);
        return $this->pdo->lastInsertId(); // Return the ID of the newly created section
    }

    // Update an existing section
    public function updateSection($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE `master_sections` SET 
                                     `section_name` = ?, 
                                     `is_active` = ?, 
                                     `created_by` = ?, 
                                     `pos_display` = ? 
                                     WHERE `id` = ?");
        $stmt->execute([
            $data['section_name'],
            $data['is_active'],
            $data['created_by'],
            $data['pos_display'],
            $id
        ]);
        return $stmt->rowCount(); // Returns the number of rows affected
    }

    // Delete a section by ID
    public function deleteSection($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `master_sections` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount(); // Returns the number of rows deleted
    }
}
