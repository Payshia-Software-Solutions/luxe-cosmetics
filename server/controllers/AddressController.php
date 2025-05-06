<?php
require_once './models/Address.php'; // Include the Address model

class AddressController
{
    private $model;

    // Constructor to initialize the Address model
    public function __construct($pdo)
    {
        $this->model = new Address($pdo);
    }

    // Create a new address record
    public function createAddress()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (
            $data && isset($data['invoice_number']) && isset($data['address_type']) &&
            isset($data['country']) && isset($data['first_name']) &&
            isset($data['last_name']) && isset($data['address']) &&
            isset($data['city']) && isset($data['postal_code']) && isset($data['phone'])
        ) {

            $this->model->createAddress($data);
            http_response_code(201);
            echo json_encode(['message' => 'Address created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    // Get addresses by invoice number
    public function getAddresses($invoice_number)
    {
        $addresses = $this->model->getAddressesByInvoice($invoice_number);
        if ($addresses) {
            echo json_encode($addresses);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'No addresses found for the given invoice number']);
        }
    }

    // Delete an address record by ID
    public function deleteAddress($address_id)
    {
        $deleted = $this->model->deleteAddress($address_id);
        if ($deleted) {
            echo json_encode(['message' => 'Address deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Address not found']);
        }
    }
}
