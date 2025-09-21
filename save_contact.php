<?php
session_start();
include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO contact_messages (name, email, message) 
            VALUES ('$name', '$email', '$message')";

    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?msg=Your message has been sent successfully!");
        exit();
    } else {
        header("Location: index.php?msg=Error: Could not save your message.");
        exit();
    }
}
?>
