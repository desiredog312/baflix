<?php
// Replace with your TMDb API key
$apiKey = 'e9e8299fd6461fe14411e00b07bc301c';

if (isset($_GET['id'])) {
    $movieId = intval($_GET['id']);
    $url = "https://api.themoviedb.org/3/movie/$movieId?api_key=$apiKey&append_to_response=credits";
    $response = file_get_contents($url);
    $movie = json_decode($response, true);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($movie['title']) ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="my-4"><?= htmlspecialchars($movie['title']) ?></h1>
    <?php if (isset($movie['poster_path'])): ?>
        <img src="https://image.tmdb.org/t/p/w500<?= $movie['poster_path'] ?>" class="img-fluid mb-4" alt="<?= htmlspecialchars($movie['title']) ?>">
    <?php endif; ?>
    <h2>Director</h2>
    <ul>
        <?php
        foreach ($movie['credits']['crew'] as $crew) {
            if ($crew['job'] == 'Director') {
                echo "<li>" . htmlspecialchars($crew['name']) . "</li>";
            }
        }
        ?>
    </ul>
    <h2>Cast</h2>
    <ul>
        <?php foreach ($movie['credits']['cast'] as $cast): ?>
            <li><?= htmlspecialchars($cast['name']) ?> as <?= htmlspecialchars($cast['character']) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
