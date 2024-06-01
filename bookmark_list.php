<?php
    require 'db.php';
    session_start();

    $user_id = $_SESSION['user_id'];

    $sql = "SELECT user_id, movie_id, title
            FROM bookmarks b
            JOIN movies m on m.tmdb_id = b.movie_id 
            WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
