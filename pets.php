<?php
session_start();
require_once 'includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$search = $_GET['search'] ?? '';

if ($search) {
    // Search by pet name, species, or breed, and join the owners table to get the owner's name
    $sql = "SELECT p.*, o.first_name, o.last_name 
            FROM pets p 
            JOIN owners o ON p.owner_id = o.owner_id 
            WHERE p.pet_name LIKE ? OR p.species LIKE ? OR p.breed LIKE ? 
            ORDER BY p.pet_id DESC";
    $stmt = $pdo->prepare($sql);
    $search_term = "%" . $search . "%";
    $stmt->execute([$search_term, $search_term, $search_term]);
} else {
    // Default view: Join tables to show all pets and their owners
    $sql = "SELECT p.*, o.first_name, o.last_name FROM pets p JOIN owners o ON p.owner_id = o.owner_id ORDER BY p.pet_id DESC";
    $stmt = $pdo->query($sql);
}

$pets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Pets - Pet Clinic</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto space-y-6">
        
        <div class="flex justify-between items-center">
            <a href="index.php" class="text-green-600 hover:underline font-semibold">&larr; Back to Dashboard</a>
            <a href="add_pet.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">+ Add New Pet</a>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-green-500">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Manage Pets</h2>

            <form action="pets.php" method="GET" class="mb-6 flex space-x-2">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by pet name, species, or breed..." class="flex-1 border border-gray-300 rounded-md p-2">
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900 transition">Search</button>
                <?php if($search): ?>
                    <a href="pets.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition text-center">Clear</a>
                <?php endif; ?>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="py-3 px-4 text-left">Pet Name</th>
                            <th class="py-3 px-4 text-left">Species/Breed</th>
                            <th class="py-3 px-4 text-left">Owner (Parent)</th>
                            <th class="py-3 px-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        <?php if(empty($pets)): ?>
                            <tr><td colspan="4" class="py-4 text-center text-gray-500">No pets found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($pets as $pet): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-2 px-4 font-bold text-green-700"><?= htmlspecialchars($pet['pet_name']) ?></td>
                                    <td class="py-2 px-4"><?= htmlspecialchars($pet['species']) ?> (<?= htmlspecialchars($pet['breed']) ?>)</td>
                                    <td class="py-2 px-4"><?= htmlspecialchars($pet['first_name'] . ' ' . $pet['last_name']) ?></td>
                                    <td class="py-2 px-4 text-center space-x-2">
                                        <a href="edit_pet.php?id=<?= $pet['pet_id'] ?>" class="text-blue-500 hover:underline text-sm font-semibold">Edit</a>
                                        <a href="delete_pet.php?id=<?= $pet['pet_id'] ?>" onclick="return confirm('Are you sure you want to delete this pet?');" class="text-red-500 hover:underline text-sm font-semibold">Delete</a>
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