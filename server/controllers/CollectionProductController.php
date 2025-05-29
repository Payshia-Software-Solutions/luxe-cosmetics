<?php
require_once './models/CollectionProduct.php';

class CollectionProductController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new CollectionProduct($pdo);
    }

    // Get all records by collection_id
    public function getAllByCollectionId($collection_id)
    {
        $records = $this->model->getByCollectionId($collection_id);
        echo json_encode($records);
    }

    // Get single record by Id
    public function getRecordById($id)
    {
        $record = $this->model->getById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Record not found']);
        }
    }

    // Create a new collection-product link
    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['collection_id']) && isset($data['product_id'])) {
            $this->model->create($data);
            http_response_code(201);
            echo json_encode(['message' => 'Record created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Update existing record
    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['collection_id']) && isset($data['product_id'])) {
            $updated = $this->model->update($id, $data);
            if ($updated) {
                echo json_encode(['message' => 'Record updated successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Record not found or no change made']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Delete record
    public function deleteRecord($id)
    {
        $deleted = $this->model->delete($id);
        if ($deleted) {
            echo json_encode(['message' => 'Record deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Record not found']);
        }
    }
}
?>