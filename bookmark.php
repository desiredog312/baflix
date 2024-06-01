<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body class="container">
    <h1>Bookmark Saya</h1>
    <ul class="list-group" id="bookmark">
            
    </ul>
</body>
</html>

<script>
    
    $(document).ready(function() {
        load_bookmark()
    })

    function load_bookmark() {
        $.get("bookmark_list.php", function(data) {
            var requests = JSON.parse(data);
            var temp = "";
            $("#bookmark").html("");
            requests.forEach((request) => {
                var requestRow = `
                    <li class="list-group-item">
                        <a href="movie_details.php?id=${request.movie_id}">
                            ${request.title}
                        </a>
                    </li>
                `;
                temp += requestRow;
            });
            $("#bookmark").html(temp);
        });
    }
</script>