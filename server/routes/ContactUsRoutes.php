<?php
require_once './controllers/ContactController.php';

$pdo = $GLOBALS['pdo'];
$contactUsController = new ContactUsController($pdo);

return [
    'POST /contact-us/' => function() use ($contactUsController) {
        $contactUsController->createMessage();
    },
    
    // Optional admin endpoints:
    'GET /contact-us/' => function() use ($contactUsController) {
        $contactUsController->getAllMessages();
    },

    'GET /contact-us/{id}/' => function($id) use ($contactUsController) {
        $contactUsController->getMessageById($id);
    },

    'DELETE /contact-us/{id}/' => function($id) use ($contactUsController) {
        $contactUsController->deleteMessage($id);
    }
];
?>
