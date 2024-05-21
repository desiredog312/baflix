<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Page</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <script>
        $(document).ready(function() {

            $("#loginBtn").click(function() {
                var username = $("#username").val();
                var password = $("#password").val();

                $.ajax({
                    type: "POST",
                    url: "login_backend.php",
                    data: {
                        username: username,
                        password: password
                    },
                    success: function(response) {
                        var jsonData = JSON.parse(response);
                        if (jsonData.success == 1) {
                            // Optionally set a session or a cookie in JavaScript, if needed.
                            window.location.href = "index.php";
                        } else {
                            alert("Incorrect username or password.");
                        }
                    }
                });
            });

            $("#registerBtn").click(function() {
                window.location.href = "register.php";
            });
        });
    </script>

</head>

<body>
    <div class="container mt-5">
        <h2>Login</h2>

        <form>
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" id="username" class="form-control">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" class="form-control">
            </div>

            <div class="d-grid gap-2">
                <button id="loginBtn" type="button" class="btn btn-primary">Login</button>
                <button id="registerBtn" type="button" class="btn btn-secondary">Register</button>
            </div>
        </form>
    </div>
</body>

</html>