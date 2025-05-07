<?php
require_once './controllers/ModeController.php';

$pdo = $GLOBALS['pdo'];
$websiteModeController = new WebsiteModeController($pdo);

return [
    // API to get the current mode
    'GET /api/get-mode' => function () use ($websiteModeController) {
        $websiteModeController->getCurrentMode();
    },

    // API to set a new mode
    'GET /api/set-mode/{mode}' => function ($mode) use ($websiteModeController) {
        $websiteModeController->setMode($mode);
    }
];
