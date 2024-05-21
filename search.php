<?php
// Replace with your TMDb API key
$apiKey = 'e9e8299fd6461fe14411e00b07bc301c';

if (isset($_GET['query'])) {
    $query = urlencode($_GET['query']);
    $url = "https://api.themoviedb.org/3/search/movie?api_key=$apiKey&query=$query";
    $response = file_get_contents($url);
    $movies = json_decode($response, true)['results'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Search</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="my-4">Search for a Movie</h1>
    <form method="get" action="search.php">
        <div class="form-group">
            <input type="text" name="query" class="form-control" placeholder="Enter movie name" required>
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
    <?php if (isset($movies)): ?>
        <h2 class="my-4">Search Results:</h2>
        <ul class="list-group">
            <?php foreach ($movies as $movie): ?>
                <li class="list-group-item">
                    <a href="movie_details.php?id=<?= $movie['id'] ?>">
                        <?= htmlspecialchars($movie['title']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
