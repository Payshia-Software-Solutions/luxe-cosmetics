<?php

class CollectionProduct
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get all records by collection_id
    public function getByCollectionId($collection_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM collection_product WHERE collection_id = ?");
        $stmt->execute([$collection_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get one record by Id
    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM collection_product WHERE Id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create new record
    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO collection_product (collection_id, product_id) VALUES (?, ?)");
        $stmt->execute([$data['collection_id'], $data['product_id']]);
        return $this->pdo->lastInsertId();
    }

    // Update record
    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE collection_product SET collection_id = ?, product_id = ? WHERE Id = ?");
        $stmt->execute([$data['collection_id'], $data['product_id'], $id]);
        return $stmt->rowCount();
    }

    // Delete record
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM collection_product WHERE Id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>