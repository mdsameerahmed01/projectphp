<?php
$servername = "localhost";   // default XAMPP host
$username = "root";          // default XAMPP user
$password = "";              // default XAMPP password
$dbname = "school_db";       // तुम्हारे database का नाम

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
