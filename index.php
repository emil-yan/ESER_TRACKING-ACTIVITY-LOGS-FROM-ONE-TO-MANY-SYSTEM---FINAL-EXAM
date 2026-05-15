<?php
session_start();

// Security Check: Kick out anyone who isn't logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Pet Clinic</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center bg-white p-6 rounded-xl shadow-md mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Pet Clinic Admin Dashboard</h1>
                <p class="text-gray-600">Welcome back, <span class="font-bold text-blue-600"><?= htmlspecialchars($_SESSION['username']) ?></span>!</p>
            </div>
            <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">Log Out</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="owners.php" class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition text-center border-t-4 border-blue-500">
                <h2 class="text-xl font-bold text-gray-800 mb-2">🐾 Manage Owners</h2>
                <p class="text-sm text-gray-500">Add, edit, search, or remove pet owners (Parent).</p>
            </a>
            
            <a href="pets.php" class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition text-center border-t-4 border-green-500">
                <h2 class="text-xl font-bold text-gray-800 mb-2">🐶 Manage Pets</h2>
                <p class="text-sm text-gray-500">Add, edit, search, or remove pets (Child).</p>
            </a>

            <a href="logs.php" class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition text-center border-t-4 border-purple-500">
                <h2 class="text-xl font-bold text-gray-800 mb-2">📋 Activity Logs</h2>
                <p class="text-sm text-gray-500">View the history of all user actions.</p>
            </a>
        </div>
    </div>
</body>
</html>