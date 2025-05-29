<?php
require_once './models/Collection.php';

class CollectionController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Collection($pdo);
    }

    // Get all collection records
    public function getAllRecords()
    {
        $records = $this->model->getAllCollections();
        echo json_encode($records);
    }

    // Get a single collection record by ID
    public function getRecordById($id)
    {
        $record = $this->model->getCollectionById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Collection not found']);
        }
    }

    // Create a new collection record
    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['Name']) && isset($data['Description'])) {
            $this->model->createCollection($data);
            http_response_code(201);
            echo json_encode(['message' => 'Collection created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update an existing collection record
    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['Name']) && isset($data['Description'])) {
            $updated = $this->model->updateCollection($id, $data);
            if ($updated) {
                echo json_encode(['message' => 'Collection updated successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Collection not found or no change made']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete a collection record
    public function deleteRecord($id)
    {
        $deleted = $this->model->deleteCollection($id);
        if ($deleted) {
            echo json_encode(['message' => 'Collection deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Collection not found']);
        }
    }
}
?>