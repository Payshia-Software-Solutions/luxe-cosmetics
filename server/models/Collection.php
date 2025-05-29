<?php

class Collection
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get all collections
    public function getAllCollections()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM collection ORDER BY Id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a collection by ID
    public function getCollectionById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM collection WHERE Id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new collection
    public function createCollection($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO collection (Name, Description) VALUES (?, ?)");
        $stmt->execute([
            $data['Name'],
            $data['Description']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Update an existing collection
    public function updateCollection($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE collection SET Name = ?, Description = ? WHERE Id = ?");
        $stmt->execute([
            $data['Name'],
            $data['Description'],
            $id
        ]);
        return $stmt->rowCount();
    }

    // Delete a collection
    public function deleteCollection($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM collection WHERE Id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>