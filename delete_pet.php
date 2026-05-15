<?php
session_start();
require_once 'includes/db_connect.php';
require_once 'includes/logger.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $pet_id = $_GET['id'];

    // Get the pet's name for the activity log
    $stmt = $pdo->prepare("SELECT pet_name FROM pets WHERE pet_id = ?");
    $stmt->execute([$pet_id]);
    $pet = $stmt->fetch();

    if ($pet) {
        $pet_name = $pet['pet_name'];

        // Delete the pet
        $delete_stmt = $pdo->prepare("DELETE FROM pets WHERE pet_id = ?");
        $delete_stmt->execute([$pet_id]);

        // --- LOG ACTIVITY ---
        logActivity($pdo, 'DELETE', 'Child: Pet', "Deleted pet: $pet_name");
    }
}

header("Location: pets.php");
exit;
?>