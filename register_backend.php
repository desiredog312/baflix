<?php
require 'db.php';

// Assuming input validation is done
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$name = $_POST['name'];

// Process profile picture
$profilePicPath = 'defaultProfilePicPath.jpg'; // Default path
if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] == 0) {
    $allowed = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png'];
    $filename = $_FILES['profilePic']['name'];
    $filetype = $_FILES['profilePic']['type'];
    $filesize = $_FILES['profilePic']['size'];

    // Verify file extension
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");

    // Verify file size - 1MB maximum
    $maxsize = 1024 * 1024;
    if ($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");

    // Verify MYME type of the file
    if (in_array($filetype, $allowed)) {
        // Check whether file exists before uploading it
        if (file_exists("upload/" . $filename)) {
            echo $filename . " is already exists.";
        } else {
            move_uploaded_file($_FILES["profilePic"]["tmp_name"], "upload/" . $filename);
            $profilePicPath = "upload/" . $filename;
        } 
    } else {
        die("Error: There was a problem uploading your file. Please try again."); 
    }
}

// Insert the new user into the database
$sql = "INSERT INTO users (username, password, name, profile_picture) VALUES (:username, :password, :name, :profile_picture)";
$stmt = $pdo->prepare($sql);
$stmt->execute(['username' => $username, 'password' => $password, 'name' => $name, 'profile_picture' => $profilePicPath]);

echo json_encode(['success' => 1]);
?>
