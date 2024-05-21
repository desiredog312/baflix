<?php
session_start();
require 'db.php';  // Include this if you need database operations

if (!isset($_POST['selectedFriend'])) {
    echo json_encode(['success' => false, 'error' => 'No friend selected']);
    exit;
}

$selectedFriend = $_POST['selectedFriend'];
$_SESSION['selectedFriend'] = $selectedFriend;
$selectedFriendID = $_POST['selectedFriendID'];
$_SESSION['selectedFriendID'] = $selectedFriendID;

// Optionally, verify that the friend is valid by checking the database
$sql = "SELECT COUNT(*) FROM users WHERE username = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$selectedFriend]);
if ($stmt->fetchColumn() > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Friend does not exist']);
}
?>
