<?php
session_start();
require_once 'includes/db_connect.php';

// Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Handle the Search functionality
$search = $_GET['search'] ?? '';

if ($search) {
    // If there is a search term, filter the results using LIKE
    $sql = "SELECT * FROM owners WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? ORDER BY owner_id DESC";
    $stmt = $pdo->prepare($sql);
    $search_term = "%" . $search . "%";
    $stmt->execute([$search_term, $search_term, $search_term]);
} else {
    // Otherwise, show everyone
    $stmt = $pdo->query("SELECT * FROM owners ORDER BY owner_id DESC");
}

$owners = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Owners - Pet Clinic</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto space-y-6">
        
        <div class="flex justify-between items-center">
            <a href="index.php" class="text-blue-600 hover:underline font-semibold">&larr; Back to Dashboard</a>
            <a href="add_owner.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">+ Add New Owner</a>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Manage Pet Owners</h2>

            <form action="owners.php" method="GET" class="mb-6 flex space-x-2">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by name or email..." class="flex-1 border border-gray-300 rounded-md p-2">
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900 transition">Search</button>
                <?php if($search): ?>
                    <a href="owners.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition text-center">Clear</a>
                <?php endif; ?>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="py-3 px-4 text-left">ID</th>
                            <th class="py-3 px-4 text-left">Name</th>
                            <th class="py-3 px-4 text-left">Email</th>
                            <th class="py-3 px-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        <?php if(empty($owners)): ?>
                            <tr><td colspan="4" class="py-4 text-center text-gray-500">No owners found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($owners as $owner): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-2 px-4"><?= $owner['owner_id'] ?></td>
                                    <td class="py-2 px-4 font-medium"><?= htmlspecialchars($owner['first_name'] . ' ' . $owner['last_name']) ?></td>
                                    <td class="py-2 px-4"><?= htmlspecialchars($owner['email']) ?></td>
                                    <td class="py-2 px-4 text-center space-x-2">
                                        <a href="edit_owner.php?id=<?= $owner['owner_id'] ?>" class="text-blue-500 hover:underline text-sm font-semibold">Edit</a>
                                        <a href="delete_owner.php?id=<?= $owner['owner_id'] ?>" onclick="return confirm('Are you sure? This will also delete all pets owned by this person.');" class="text-red-500 hover:underline text-sm font-semibold">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>