<?php
session_start();
require_once 'includes/db_connect.php';
require_once 'includes/logger.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$owner_id = $_GET['id'] ?? null;

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_owner'])) {
    $id = $_POST['owner_id'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    $sql = "UPDATE owners SET first_name = ?, last_name = ?, email = ?, phone_number = ? WHERE owner_id = ?";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$first_name, $last_name, $email, $phone, $id])) {
        // --- RECORD THE UPDATE ACTIVITY ---
        logActivity($pdo, 'UPDATE', 'Parent: Owner', "Updated owner details for: $first_name $last_name (ID: $id)");
        
        header("Location: owners.php?success=Owner updated successfully");
        exit;
    }
}

// Fetch existing data to pre-fill the form
if ($owner_id) {
    $stmt = $pdo->prepare("SELECT * FROM owners WHERE owner_id = ?");
    $stmt->execute([$owner_id]);
    $owner = $stmt->fetch();
    if (!$owner) die("Owner not found.");
} else {
    die("No owner selected.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Owner - Pet Clinic</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md p-6">
        <div class="mb-4">
            <a href="owners.php" class="text-blue-600 hover:underline">&larr; Back to Owners</a>
        </div>
        <h2 class="text-2xl font-bold mb-4 text-blue-600">Edit Owner Details</h2>
        
        <form action="edit_owner.php" method="POST" class="space-y-4">
            <input type="hidden" name="owner_id" value="<?= $owner['owner_id'] ?>">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" name="first_name" value="<?= htmlspecialchars($owner['first_name']) ?>" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" name="last_name" value="<?= htmlspecialchars($owner['last_name']) ?>" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" name="email" value="<?= htmlspecialchars($owner['email']) ?>" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($owner['phone_number']) ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
            </div>
            <button type="submit" name="update_owner" class="w-full bg-blue-600 text-white font-bold py-2 rounded hover:bg-blue-700 transition">
                Update Owner
            </button>
        </form>
    </div>
</body>
</html>