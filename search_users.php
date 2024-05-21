<?php
require 'db.php';
session_start();

$searchUsername = $_POST['searchUsername'] ?? '';
$user_id = $_SESSION['user_id'];

$sql = "SELECT id, username, profile_picture FROM users WHERE username LIKE ? AND id != ?";
$stmt = $pdo->prepare($sql);
$stmt->execute(["%$searchUsername%", $user_id]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Enhance results with friendship status
foreach ($users as $key => $user) {
    // Check if already friends
    $sql = "SELECT COUNT(*) FROM friendships WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $user['id'], $user['id'], $user_id]);
    $isFriend = $stmt->fetchColumn() > 0;

    // Check if there is a pending friend request
    $sql = "SELECT status FROM friend_requests WHERE (from_user_id = ? AND to_user_id = ?) OR (from_user_id = ? AND to_user_id = ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $user['id'], $user['id'], $user_id]);
    $request = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($isFriend) {
        $users[$key]['actionButton'] = "<p>Already Friends</p>";
    } elseif ($request) {
        if ($request['status'] == 'PENDING') {
            $users[$key]['actionButton'] = "<p>Request Pending</p>";
        }
    } else {
        $users[$key]['actionButton'] = "<button onclick='sendFriendRequest(\"{$user['username']}\", this)'>Add Friend</button>";
    }
}

echo json_encode($users);
?>
