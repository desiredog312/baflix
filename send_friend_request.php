<?php
require 'db.php';
session_start();

$to_username = $_POST['username'];
$from_user_id = $_SESSION['user_id'];

// Fetch the user ID of the recipient of the friend request
$sql = "SELECT id FROM users WHERE username = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$to_username]);
$to_user_id = $stmt->fetchColumn();

if (!$to_user_id) {
    echo json_encode(['success' => false, 'error' => 'User not found']);
    exit;
}

// Check if there's already a request or a friendship
$sql = "SELECT COUNT(*) FROM friend_requests WHERE (from_user_id = ? AND to_user_id = ?) OR (from_user_id = ? AND to_user_id = ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$from_user_id, $to_user_id, $to_user_id, $from_user_id]);
if ($stmt->fetchColumn() > 0) {
    echo json_encode(['success' => false, 'error' => 'Friend request already sent or there is already a friendship']);
    exit;
}

// Insert the new friend request
$sql = "INSERT INTO friend_requests (from_user_id, to_user_id, status) VALUES (?, ?, 'PENDING')";
$stmt = $pdo->prepare($sql);
if ($stmt->execute([$from_user_id, $to_user_id])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Could not insert friend request']);
}
