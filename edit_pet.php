<?php
session_start();
require_once 'includes/db_connect.php';
require_once 'includes/logger.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$pet_id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_pet'])) {
    $id = $_POST['pet_id'];
    $owner_id = $_POST['owner_id'];
    $pet_name = trim($_POST['pet_name']);
    $species = trim($_POST['species']);
    $breed = trim($_POST['breed']);
    $birth_date = $_POST['birth_date'];

    $sql = "UPDATE pets SET owner_id = ?, pet_name = ?, species = ?, breed = ?, birth_date = ? WHERE pet_id = ?";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$owner_id, $pet_name, $species, $breed, $birth_date, $id])) {
        // --- LOG ACTIVITY ---
        logActivity($pdo, 'UPDATE', 'Child: Pet', "Updated details for pet: $pet_name (ID: $id)");
        header("Location: pets.php?success=Pet updated successfully");
        exit;
    }
}

if ($pet_id) {
    $stmt = $pdo->prepare("SELECT * FROM pets WHERE pet_id = ?");
    $stmt->execute([$pet_id]);
    $pet = $stmt->fetch();
    if (!$pet) die("Pet not found.");
} else {
    die("No pet selected.");
}

$owners_stmt = $pdo->query("SELECT owner_id, first_name, last_name FROM owners");
$owners = $owners_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Pet - Pet Clinic</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md p-6 border-t-4 border-green-500">
        <div class="mb-4">
            <a href="pets.php" class="text-green-600 hover:underline">&larr; Back to Pets</a>
        </div>
        <h2 class="text-2xl font-bold mb-4 text-green-600">Edit Pet Details</h2>
        
        <form action="edit_pet.php" method="POST" class="space-y-4">
            <input type="hidden" name="pet_id" value="<?= $pet['pet_id'] ?>">

            <div>
                <label class="block text-sm font-medium text-gray-700">Owner</label>
                <select name="owner_id" required class="mt-1 block w-full border border-gray-300 rounded-md p-2 bg-white">
                    <?php foreach ($owners as $owner): ?>
                        <option value="<?= $owner['owner_id'] ?>" <?= ($owner['owner_id'] == $pet['owner_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($owner['first_name'] . ' ' . $owner['last_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Pet Name</label>
                <input type="text" name="pet_name" value="<?= htmlspecialchars($pet['pet_name']) ?>" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Species</label>
                    <input type="text" name="species" value="<?= htmlspecialchars($pet['species']) ?>" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Breed</label>
                    <input type="text" name="breed" value="<?= htmlspecialchars($pet['breed']) ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Birth Date</label>
                <input type="date" name="birth_date" value="<?= htmlspecialchars($pet['birth_date']) ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
            </div>

            <button type="submit" name="update_pet" class="w-full bg-green-600 text-white font-bold py-2 rounded hover:bg-green-700 transition">
                Update Pet
            </button>
        </form>
    </div>
</body>
</html>