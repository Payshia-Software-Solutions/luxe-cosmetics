<?php
require_once './controllers/ContactMessagesController.php';

$pdo = $GLOBALS['pdo'];
$contactMessagesController = new ContactMessagesController($pdo);

return [
    'POST /contact/' => function () use ($contactMessagesController) {
        $contactMessagesController->createRecord();
    },
    'GET /contact/' => function () use ($contactMessagesController) {
        $contactMessagesController->getAllRecords();
    },
    'GET /contact/{id}/' => function ($id) use ($contactMessagesController) {
        $contactMessagesController->getRecordById($id);
    },
    'DELETE /contact/{id}/' => function ($id) use ($contactMessagesController) {
        $contactMessagesController->deleteRecord($id);
    },
    'GET /sendnewsletter/' => function () use ($contactMessagesController) {
        $contactMessagesController->sendNewsLetter();
    }
];
