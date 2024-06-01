<?php
require 'db.php';
session_start();

$user_id = $_SESSION['user_id'];
$movie_id = $_POST['id'];

if (isset($_POST['id'])) {
    $sql = "SELECT user_id, movie_id FROM bookmarks WHERE user_id = :user_id AND movie_id = :movie_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id,'movie_id' => $movie_id]);
    $exists = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(!$exists)
    {
        // Insert the bookmark into the database
        $sql = "INSERT INTO bookmarks (user_id, movie_id) VALUES (:user_id, :movie_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $user_id, 'movie_id' => $movie_id]);

        echo json_encode(['success' => 1, 'message'=> "berhasil bookmark"]);
    }
    else
    {
        // Insert the bookmark into the database
        $sql = "DELETE FROM bookmarks WHERE user_id = :user_id AND movie_id = :movie_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $user_id, 'movie_id' => $movie_id]);

        echo json_encode(['success' => 0, 'message'=> "berhasil hapus bookmark"]);
    }
}else{
    echo json_encode(['success' => 0, 'message'=> "movie tidak ada"]);
}


?>
