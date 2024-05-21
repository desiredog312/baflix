<?php
require 'db.php';
session_start();

$user_id = $_SESSION['user_id'];
$sql = "SELECT u.id, u.username, u.name FROM friendships f JOIN users u ON u.id = f.friend_id WHERE f.user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$friends = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($friends);
?>
