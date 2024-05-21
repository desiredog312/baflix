<?php
require 'db.php';
session_start();

$user_id = $_SESSION['user_id'];
$friend_id = $_SESSION['selectedFriendID']; // Assuming you send the friend's ID

$sql = "SELECT c.message, c.timestamp, u.username AS sender FROM chats c JOIN users u ON c.sender_id = u.id WHERE (c.sender_id = ? AND c.receiver_id = ?) OR (c.sender_id = ? AND c.receiver_id = ?) ORDER BY c.timestamp ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $friend_id, $friend_id, $user_id]);
$chats = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($chats);
?>
