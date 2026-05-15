<?php
session_start();
require_once 'includes/db_connect.php';

// Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch all logs, joining with the users table to get the username
// We order by timestamp DESC so the newest actions are at the top
$sql = "SELECT a.*, u.username 
        FROM activity_logs a 
        JOIN users u ON a.user_id = u.user_id 
        ORDER BY a.timestamp DESC";
$stmt = $pdo->query($sql);
$logs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Activity Logs - Pet Clinic</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto space-y-6">
        
        <div class="mb-4">
            <a href="index.php" class="text-purple-600 hover:underline font-semibold">&larr; Back to Dashboard</a>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-purple-500">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">System Activity Logs</h2>
            <p class="text-sm text-gray-500 mb-6">Read-only record of all administrative actions performed in the system.</p>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 text-sm">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="py-3 px-4 text-left">Date & Time</th>
                            <th class="py-3 px-4 text-left">Staff Username</th>
                            <th class="py-3 px-4 text-left">Action</th>
                            <th class="py-3 px-4 text-left">Entity Level</th>
                            <th class="py-3 px-4 text-left">Details</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        <?php if(empty($logs)): ?>
                            <tr><td colspan="5" class="py-4 text-center text-gray-500">No activity logged yet.</td></tr>
                        <?php else: ?>
                            <?php foreach ($logs as $log): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4 whitespace-nowrap"><?= date('M d, Y h:i A', strtotime($log['timestamp'])) ?></td>
                                    <td class="py-3 px-4 font-bold text-purple-600"><?= htmlspecialchars($log['username']) ?></td>
                                    <td class="py-3 px-4">
                                        <?php 
                                            // Add some color coding for different actions
                                            $action_color = 'bg-gray-200 text-gray-800';
                                            if ($log['action'] == 'CREATE') $action_color = 'bg-green-100 text-green-800';
                                            if ($log['action'] == 'UPDATE') $action_color = 'bg-blue-100 text-blue-800';
                                            if ($log['action'] == 'DELETE') $action_color = 'bg-red-100 text-red-800';
                                        ?>
                                        <span class="<?= $action_color ?> px-2 py-1 rounded text-xs font-bold">
                                            <?= htmlspecialchars($log['action']) ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 font-semibold text-gray-600"><?= htmlspecialchars($log['entity']) ?></td>
                                    <td class="py-3 px-4"><?= htmlspecialchars($log['details']) ?></td>
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