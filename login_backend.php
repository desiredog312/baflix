<?php
require 'db.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// SQL query to check if the username exists.
$sql = "SELECT id, password FROM users WHERE username = :username";
$stmt = $pdo->prepare($sql);
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    // Login successful
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $username;
    echo json_encode(['success' => 1]);
} else {
    // Login failed
    echo json_encode(['success' => 0]);
}
?>
