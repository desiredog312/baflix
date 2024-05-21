<?php
session_start();
require 'db.php';

if (isset($_SESSION['selectedFriendID'])) {
    $friend_id = $_SESSION['selectedFriendID'];
    $sql = "SELECT username FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$friend_id]);
    $friendUsername = $stmt->fetchColumn();

    echo json_encode(['success' => true, 'username' => $friendUsername]);
} else {
    echo json_encode(['success' => false, 'error' => 'No friend selected']);
}
?>
