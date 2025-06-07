<?php
session_start();
require_once('user.class.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['email'])) {
        echo "User not logged in.";
        exit();
    }

    $email = $_SESSION['email'];

    if ($_FILES['new_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'img/';
        $uploadPath = $uploadDir . basename($_FILES['new_image']['name']);
    
        if (move_uploaded_file($_FILES['new_image']['tmp_name'], $uploadPath)) {
            $user = new User();
            $result = $user->updateImg($email, $uploadPath);
            header('location: dashboard/settings.php');
    }
    }}
?>
