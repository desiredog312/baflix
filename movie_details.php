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
    <h1 class="mt-4"><?= htmlspecialchars($movie['title']) ?></h1>
    <button class="btn btn-primary mb-4" onclick="addmovie()" id="addmovie">Add Movie</button>
    <button class="btn btn-success mb-4" onclick="bookmark()" id="bookmark">Bookmark</button>
    <div>
        <?php if (isset($movie['poster_path'])): ?>
            <img src="https://image.tmdb.org/t/p/w500<?= $movie['poster_path'] ?>" class="img-fluid mb-4" alt="<?= htmlspecialchars($movie['title']) ?>">
        <?php endif; ?>
    </div>
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
    <div class="row">
        <!-- <ul> -->
        <?php foreach ($movie['credits']['cast'] as $cast): ?>
                <!-- <li><?= htmlspecialchars($cast['name']) ?> as <?= htmlspecialchars($cast['character']) ?></li> -->
                <div class="col-4">
                    <li><?= htmlspecialchars($cast['name']) ?> as <?= htmlspecialchars($cast['character']) ?></li>
                </div>
            <?php endforeach; ?>
        <!-- </ul> -->
    </div>
</div>
<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<script>
    $(document).ready(function() {
        check_bookmark()
    })
    var entityMap = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#39;',
        '/': '&#x2F;',
        '`': '&#x60;',
        '=': '&#x3D;'
    };  

    function escapeHtml (string) {
        return String(string).replace(/[&<>"'`=\/]/g, function (s) {
            return entityMap[s];
        });
    }

    function addmovie(){
        $.post({
            url: "movie_backend.php",
            data: {
                movie_id: '<?= $movie['id'] ?>',
                title: escapeHtml("<?= $movie['title'] ?>"),
                release_date: '<?= $movie['release_date'] ?>',
                overview: escapeHtml("<?= $movie['overview'] ?>"),
                poster_path: '<?= $movie['poster_path'] ?>'
            },
            success: function(response) {
                var jsonData = JSON.parse(response);
                alert(jsonData.message)
            }
        });
    }

    function bookmark(){
        var id = '<?= $_GET['id'] ?>'
        $.post({
            url: "bookmark_backend.php",
            data: {
                id: id
            },
            success: function(response) {
                var jsonData = JSON.parse(response);
                alert(jsonData.message)
                check_bookmark();
            }
        });
    }
    
    function check_bookmark(){
        var id = '<?= $_GET['id'] ?>'
        $.post({
            url: "bookmark_check.php",
            data: {
                id: id
            },
            success: function(response) {
                var jsonData = JSON.parse(response);
                if(jsonData.success == 1){
                    $("#bookmark").attr('class','btn btn-danger mb-4')
                    $("#bookmark").html('Hapus Bookmark')
                }else{
                    $("#bookmark").attr('class','btn btn-success mb-4')
                    $("#bookmark").html('Bookmark')
                }
            }
        });
    }
</script>

