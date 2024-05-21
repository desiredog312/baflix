<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register Page</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />

    <script>
        $(document).ready(function() {
            $("#registerBtn").click(function(e) {
                e.preventDefault();

                var formData = new FormData();
                formData.append('username', $("#username").val());
                formData.append('name', $("#name").val());
                formData.append('password', $("#password").val());
                formData.append('profilePic', $('#profilePic')[0].files[0]); // Add the file

                $.ajax({
                    type: "POST",
                    url: "register_backend.php", // PHP file to handle the form
                    data: formData,
                    contentType: false, // Important for file upload
                    processData: false, // Important for file upload
                    success: function(response) {
                        console.log(response);
                        var jsonData = JSON.parse(response);
                        if (jsonData.success == 1) {
                            alert("Registration successful!");
                            window.location.href = "login.php";
                        } else {
                            alert(jsonData.error);
                        }
                    }
                });
            });

            $("#loginBtn").click(function() {
                window.location.href = "login.php";
            });
        });
    </script>
</head>

<body>
    <div class="container mt-5">
        <h2>Register</h2>
        <form id="registerForm" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" id="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="profilePic" class="form-label">Profile Picture:</label>
                <input type="file" id="profilePic" class="form-control" accept=".jpg, .jpeg, .png" required>
            </div>
            <div class="d-grid gap-2 d-md-block">
                <button id="loginBtn" type="button" class="btn btn-secondary">Login</button>
                <button id="registerBtn" type="submit" class="btn btn-primary">Register</button>
            </div>
        </form>
    </div>
</body>

</html>