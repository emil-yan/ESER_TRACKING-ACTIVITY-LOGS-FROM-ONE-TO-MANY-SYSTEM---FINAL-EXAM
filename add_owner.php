<?php
session_start();
require_once 'includes/db_connect.php';
require_once 'includes/logger.php'; // Include our new logger!

// Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_owner'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    $sql = "INSERT INTO owners (first_name, last_name, email, phone_number) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$first_name, $last_name, $email, $phone])) {
        // --- THIS IS THE MAGIC LINE FOR YOUR EXAM ---
        logActivity($pdo, 'CREATE', 'Parent: Owner', "Added new owner: $first_name $last_name");
        
        header("Location: owners.php?success=Owner added successfully");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Owner - Pet Clinic</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md p-6">
        <div class="mb-4">
            <a href="owners.php" class="text-blue-600 hover:underline">&larr; Back to Owners</a>
        </div>
        <h2 class="text-2xl font-bold mb-4 text-blue-600">Add New Owner</h2>
        
        <form action="add_owner.php" method="POST" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" name="first_name" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" name="last_name" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" name="email" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input type="text" name="phone" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
            </div>
            <button type="submit" name="add_owner" class="w-full bg-blue-600 text-white font-bold py-2 rounded hover:bg-blue-700 transition">
                Save Owner
            </button>
        </form>
    </div>
</body>
</html>