<?php
require 'db.php';
session_start();

$user_id = $_SESSION['user_id'];
$sql = "SELECT u.id, u.username FROM friend_requests fr JOIN users u ON u.id = fr.from_user_id WHERE fr.to_user_id = ? AND fr.status = 'PENDING'";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($requests);
?>
