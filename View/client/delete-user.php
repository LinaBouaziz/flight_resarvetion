<?php
require_once __DIR__ . '/../../confi/db.php';
require_once __DIR__ . '/../../Model/client.php';

if (isset($_GET['id'])) {
    $clientId = $_GET['id'];

    $clientModel = new Client($pdo);
    $success = $clientModel->deleteClientById($clientId);

    if ($success) {
        header("Location: manage-users.php?deleted=1");
        exit();
    } else {
        header("Location: manage-users.php?deleted=0");
        exit();
    }
} else {
    echo "Invalid request.";
}
