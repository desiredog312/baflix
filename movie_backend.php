<?php
require 'db.php';
session_start();

$movie_id = $_POST['movie_id'];
$title = $_POST['title'];
$release_date = $_POST['release_date'];
$overview = $_POST['overview'];
$poster_path = $_POST['poster_path'];

if (isset($_POST['movie_id'])) {
    $sql = "SELECT tmdb_id FROM movies WHERE tmdb_id = :movie_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['movie_id' => $movie_id]);
    $exists = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(!$exists)
    {
        $sql = "INSERT INTO movies (tmdb_id, title, release_date, overview, poster_path) 
            VALUES (:movie_id, :title, :release_date, :overview, :poster_path)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'movie_id' => $movie_id, 
            'title' => $title, 
            'release_date' => $release_date, 
            'overview' => $overview, 
            'poster_path' => $poster_path
        ]);

        echo json_encode(['success' => 1, 'message'=> "berhasil menambah movie"]);
    }
    else
    {

        echo json_encode(['success' => 0, 'message'=> "movie sudah ada"]);
    }
}else{
    echo json_encode(['success' => 0, 'message'=> "movie tidak ada"]);
}


?>
