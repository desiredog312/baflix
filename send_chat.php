<?php
require 'db.php';
session_start();

if (!isset($_POST['message'])) {
    echo json_encode(['success' => false, 'error' => 'Message parameter missing']);
    exit;
}

if (!isset($_SESSION['selectedFriendID'])) {
    echo json_encode(['success' => false, 'error' => 'No friend selected']);
    exit;
}

$user_id = $_SESSION['user_id'];
$friend_id = $_SESSION['selectedFriendID'];
$message = $_POST['message'];

// Debugging output
error_log("User ID: $user_id, Friend ID: $friend_id, Message: $message");

$sql = "INSERT INTO chats (sender_id, receiver_id, message) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);

if ($stmt->execute([$user_id, $friend_id, $message])) {
    echo json_encode(['success' => true]);
} else {
    error_log("SQL Error: " . implode(", ", $stmt->errorInfo()));
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
?>
