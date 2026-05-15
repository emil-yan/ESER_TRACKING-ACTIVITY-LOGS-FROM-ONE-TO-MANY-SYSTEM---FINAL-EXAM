<?php
// includes/logger.php

/**
 * Helper function to record actions to the activity_logs table.
 * * @param PDO $pdo The active database connection
 * @param string $action 'CREATE', 'READ', 'UPDATE', or 'DELETE'
 * @param string $entity 'Parent: Owner' or 'Child: Pet'
 * @param string $details A descriptive message of what happened
 */
function logActivity($pdo, $action, $entity, $details) {
    // Make sure a session is active so we can grab the logged-in user's ID
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // If no user is logged in, we can't accurately track the action
    if (!isset($_SESSION['user_id'])) {
        return false; 
    }

    $user_id = $_SESSION['user_id'];

    // Insert the log into the database
    $sql = "INSERT INTO activity_logs (user_id, action, entity, details) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    return $stmt->execute([$user_id, $action, $entity, $details]);
}
?>