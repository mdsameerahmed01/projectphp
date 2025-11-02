<?php
require_once __DIR__ . '/session_cookie/session_init.php';
include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $comments = mysqli_real_escape_string($conn, $_POST['comments']);

    $sql = "INSERT INTO contact_messages (name, email, comments) 
            VALUES ('$name', '$email', '$comments')";

    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?msg=Your message has been sent successfully!");
        exit();
    } else {
        header("Location: index.php?msg=Error: Could not save your message.");
        exit();
    }
}
?>
