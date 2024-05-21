<?php
require 'db.php';
session_start();

$to_username = $_POST['username'];
$from_user_id = $_SESSION['user_id'];

// Fetch the user ID of the person who sent the friend request
$sql = "SELECT id FROM users WHERE username = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$to_username]);
$to_user_id = $stmt->fetchColumn();

if (!$to_user_id) {
    echo json_encode(['success' => false, 'error' => 'User not found']);
    exit;
}

// Update the friend request status to "DECLINED"
$sql = "UPDATE friend_requests SET status = 'DECLINED' WHERE from_user_id = ? AND to_user_id = ?";
$stmt = $pdo->prepare($sql);
$result = $stmt->execute([$to_user_id, $from_user_id]);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to update friend request status']);
}
?>
