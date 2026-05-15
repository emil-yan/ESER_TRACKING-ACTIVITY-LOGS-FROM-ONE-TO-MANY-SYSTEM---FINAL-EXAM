<?php
session_start();
require_once 'includes/db_connect.php';
require_once 'includes/logger.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $owner_id = $_GET['id'];

    // First, get the owner's name so we can log exactly who we deleted
    $stmt = $pdo->prepare("SELECT first_name, last_name FROM owners WHERE owner_id = ?");
    $stmt->execute([$owner_id]);
    $owner = $stmt->fetch();

    if ($owner) {
        $full_name = $owner['first_name'] . ' ' . $owner['last_name'];

        // Now, delete the owner (and cascade delete their pets)
        $delete_stmt = $pdo->prepare("DELETE FROM owners WHERE owner_id = ?");
        $delete_stmt->execute([$owner_id]);

        // --- RECORD THE DELETE ACTIVITY ---
        logActivity($pdo, 'DELETE', 'Parent: Owner', "Deleted owner: $full_name");
    }
}

header("Location: owners.php");
exit;
?>